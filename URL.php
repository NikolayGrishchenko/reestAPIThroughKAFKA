<?php
namespace App\API;
class URL
{
    private Array $urlArray;
    
    public function __construct()
    {
        $this->urlArray = explode("/",$_SERVER["REQUEST_URI"]);
        array_shift($this->urlArray);
        if (empty($this->urlArray[array_key_last($this->urlArray)]))
        {

            array_pop($this->urlArray);
        }
    }
    
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
    
    public function proccessingUrl()
    {
        if (!empty($this->urlArray) && $_SERVER["REQUEST_METHOD"] == "GET")
        {

            if ($this->urlArray[0] == "books")
            {

                if (!empty($this->urlArray[1]))
                {
                    $books = new Books();
                    $books->fetchBook($this->urlArray[1]);
                } else
                {
                    $books = new Books();
                    $books->fetchBooks();
                }
            }
        }

        if (!empty($this->urlArray) && $this->urlArray[0] == "books" && $_SERVER["REQUEST_METHOD"] == "POST")
        {
            $books = new Books();
            $books->createBook($_POST);
        }

        if (!empty($this->urlArray) && $_SERVER["REQUEST_METHOD"] == "PUT")
        {
            if ($this->urlArray[0] == "books" && !empty($this->urlArray[1]))
            {
                $data = json_decode(file_get_contents("php://input"));
                $books = new Books();
                $books->updateBook($this->urlArray[1], $data);
            }
        }

        if (!empty($this->urlArray) && $_SERVER["REQUEST_METHOD"] == "DELETE")
        {
            if ($this->urlArray[0] == "books" && !empty($this->urlArray[1]))
            {
                $books = new Books();
                $books->deleteBook($this->urlArray[1]);
            }
        }
    }
}

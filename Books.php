<?php
namespace App\API;
use \App\API\Kafka\Producer;

interface Observer
{
    public function update(string $message, string $key): void;
}

interface Observable
{
    public function registerObserver(Observer $observer): void;
    public function removeObserver(Observer $observer): void;
    public function notifyObservers(): void;
}

class ResponseHttp implements Observable
{
    private array $observers = [];
    private int $responseCode;
    private array $message;

    private function printMsg(int $responseCode, array $message): void
    {
        http_response_code($responseCode);
        print_r(json_encode($message));


        //producer start
        //self::$producer->sendMessage("httpAnswer",json_encode($message), "key");

        //echo "### " .self::$test. "\n";
        //self::printStaticProperty();
        //echo "### " .self::$test. "\n";

//        $conf = new \RdKafka\Conf();
//        $conf->set('bootstrap.servers', 'kafka:9092'); // Replace with your Kafka brokers
//        $conf->set('client.id', 'php-producer-example');
//        $conf->set('request.required.acks', 'all'); // Ensure all replicas acknowledge the message
//
//// Optional: Set a delivery report callback to handle message delivery status
//        $conf->setDrMsgCb(function ($kafka, $message) {
//            if ($message->err) {
//                echo "Message delivery failed: " . $message->errstr() . "\n";
//            } else {
//                echo "Message delivered successfully to topic " . $message->topic_name . " partition " . $message->partition . " offset " . $message->offset . "\n";
//            }
//        });
//
//// 2. Producer Initialization
//        $producer = new \RdKafka\Producer($conf);
//
//// 3. Topic Configuration
//        $topicName = 'test777'; // Replace with your topic name
//        $topicConf = new \RdKafka\TopicConf();
//        $producerTopic = $producer->newTopic($topicName, $topicConf);
//
//// 4. Message Production
//        //$messagePayload = 'Hello, Kafka from PHP!';
//        $messageKey = 'my_key';
//
//        //for ($i = 0; $i < 5; $i++) {
//            $producerTopic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($message),$messageKey);
//            $producer->poll(0); // Poll for delivery reports without blocking
//        //}
//
//// 5. Polling and Flushing
//        //echo "Flushing producer..." . "\n";
//        $result = $producer->flush(10000); // Flush with a 10-second timeout
//
//        if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
//            echo "Messages flushed successfully." . "\n";
//        } else {
//            echo "Failed to flush messages: " . $result . "\n";
//        }

        //producer end

    }
    public function __construct(int $responseCode, Array $message)
    {
        $this->responseCode = $responseCode;
        $this->message = $message;
        $this->printMsg($responseCode, $message);

        $producer = new Producer("httpAnswer", $this);

        $this->notifyObservers();
    }
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    public function registerObserver(Observer $observer): void
    {
        // TODO: Implement registerObserver() method.
        $this->observers[] = $observer;
    }

    public function removeObserver(Observer $observer): void
    {
        // TODO: Implement removeObserver() method.
        $index = array_search($observer, $this->observers, true);
        if ($index !== false) {
            array_splice($this->observers, $index, 1);
        }
    }

    public function notifyObservers(): void
    {
        // TODO: Implement notifyObservers() method.
        foreach ($this->observers as $observer) {

            $observer->update(json_encode($this->message), "key");
        }
    }
}
class Books
{
    private DB $db;

    public function __construct()
    {
        $this->db = new DB('mysql', 'user', 'password', 'books');
    }
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    public function fetchBooks(): Void
    {
        $query = mysqli_query($this->db->db, "SELECT * FROM books");
        $queryArray = mysqli_fetch_all($query, MYSQLI_ASSOC);
        new ResponseHttp(200, $queryArray);
    }
    public function fetchBook(int $id): Void
    {
        $query = mysqli_query($this->db->db, "SELECT * FROM books WHERE id=$id");
        $queryArray = mysqli_fetch_all($query, MYSQLI_ASSOC);
        if (count($queryArray) > 0) {

            new ResponseHttp(200, $queryArray);
        } else {

            new ResponseHttp(404, ["status" => false, "message" => "Book not found"]);
        }
    }

    private function createQueryWithParametrs(Array $dataArray): string
    {
        $queryStr = "";
        $accessableKeys = ["name", "authors", "description"];
        foreach($dataArray as $key => $value)
        {
            if (!in_array($key, $accessableKeys) || $value == "") continue;
            $queryStr .= $queryStr == "" ? $key . " = " . "'" . $value . "'" : ", ".$key . " = " . "'" . $value . "'";
        }
        return $queryStr;
    }

    public function createBook(Array $post): Void
    {
        $name = $post["name"];
        $authors = $post["authors"];
        $description = !empty($post["description"]) ? $post["description"] : NULL;
        if (!empty($name) && !empty($authors))
        {
            mysqli_query($this->db->db, "INSERT INTO books (name, authors, description) VALUES ('$name', '$authors', '$description')");
            if ($id = mysqli_insert_id($this->db->db))
            {
                new ResponseHttp(201, ["status" => true, "message" => "Book was created with id $id"]);
            } else {

                new ResponseHttp(500, ["status" => false, "message" => "The query was not processed"]);
            }
        } else {
            new ResponseHttp(400, ["status" => false, "message" => "Not enough parameters for request"]);
        }
    }

    public function updateBook(int $id, Mixed $data): Void
    {
        $dataArray = (Array) $data;
        if (count($dataArray) > 0)
        {
            if (($queryStr = $this->createQueryWithParametrs($dataArray)) != "")
            {
                mysqli_query($this->db->db, "UPDATE books SET ".$queryStr."  WHERE ID=$id");
                if (mysqli_affected_rows($this->db->db) > 0)
                {
                    new ResponseHttp(202, ["status" => true, "message" => "Book was updated with id $id"]);
                } else
                {
                    new ResponseHttp(404, ["status" => false, "message" => "Book doesn't exist with id $id"]);
                }
            } else
            {
                new ResponseHttp(400, ["status" => false, "message" => "Your query has bad parameters"]);
            }
        } else
        {
            new ResponseHttp(400, ["status" => false, "message" => "Your query has not parameters"]);
        }
    }

    public function deleteBook(int $id): Void
    {
        mysqli_query($this->db->db, "DELETE FROM books WHERE ID=$id");
        if (mysqli_affected_rows($this->db->db) > 0)
        {
            new ResponseHttp(200, ["status" => true, "message" => "Book was deleted with id $id"]);
        } else
        {
            new ResponseHttp(404, ["status" => false, "message" => "Book doesn't exist with id $id"]);
        }
    }
}

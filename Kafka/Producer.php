<?php
namespace App\API\Kafka;

use App\API\Observable;
use App\API\Observer;
class Producer implements Observer {

    private \RdKafka\Conf $conf;
    private \RdKafka\Producer $producer;
    private  \RdKafka\ProducerTopic $producerTopic;

    public function sendMessage(string $message, string $key): Void
    {
        $this->producerTopic->produce(RD_KAFKA_PARTITION_UA, 0, $message, $key);
        $this->producer->poll(0); // Poll for delivery reports without blocking
        $this->producer->flush(10000); // Flush with a 10-second timeout
//        if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
//            echo "Messages flushed successfully." . "\n";
//        } else {
//            echo "Failed to flush messages: " . $result . "\n";
//        }
    }

    private function createTopic(string $topicName): Void
    {
        $topicConf = new \RdKafka\TopicConf();
        $this->producerTopic = $this->producer->newTopic($topicName, $topicConf);
    }

    public function __construct(string $topic, Observable $responseHttp)
    {
        $this->conf = new \RdKafka\Conf();
        $this->conf->set('bootstrap.servers', 'kafka:9092'); // Replace with your Kafka brokers
        $this->conf->set('client.id', 'php-producer-example');
        $this->conf->set('request.required.acks', 'all'); // Ensure all replicas acknowledge the message
//        Optional: Set a delivery report callback to handle message delivery status
//        $conf->setDrMsgCb(function ($kafka, $message) {
//            if ($message->err) {
//                echo "Message delivery failed: " . $message->errstr() . "\n";
//            } else {
//                echo "Message delivered successfully to topic " . $message->topic_name . " partition " . $message->partition . " offset " . $message->offset . "\n";
//            }
//        });
        $this->producer = new \RdKafka\Producer($this->conf);

        $this->createTopic($topic);

        $responseHttp->registerObserver($this);
    }
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    public function update(string $message, string $key): void
    {
        // TODO: Implement update() method.
        $this->sendMessage($message, $key);
    }
}
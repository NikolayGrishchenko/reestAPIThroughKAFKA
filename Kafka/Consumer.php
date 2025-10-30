<?php
$conf = new \RdKafka\Conf();
$conf->set('bootstrap.servers', 'kafka:9092'); // Kafka broker list
$conf->set('group.id', 'my-consumer-group'); // Consumer group ID
$conf->set('auto.offset.reset', 'earliest'); // Start consuming from the beginning if no offset is found

// Optional: Set a callback for logging
$conf->setLogCb(function ($kafka, $level, $facility, $message) {
    error_log("Kafka Log: [$facility] $message");
});

$consumer = new \RdKafka\KafkaConsumer($conf);
$consumer->subscribe(['httpAnswer']); // Subscribe to one or more topics

echo "Waiting for messages...\n";

while (true) {
    $message = $consumer->consume(120*1000); // Consume messages with a timeout of 120 seconds

    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            echo "Received message: " . $message->payload . "\n";
            // Process the message here
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "No more messages for this partition.\n";
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            echo "Consumer timed out.\n";
            break;
        default:
            throw new \Exception($message->errstr(), $message->err);
    }
}
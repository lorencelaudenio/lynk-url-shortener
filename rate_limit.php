<?php

function rateLimit($key, $limit = 10, $seconds = 60) {

    $file = sys_get_temp_dir() . "/rl_" . md5($key);

    $data = [
        "count" => 0,
        "time" => time()
    ];

    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
    }

    if (time() - $data["time"] > $seconds) {
        $data = ["count" => 0, "time" => time()];
    }

    $data["count"]++;

    file_put_contents($file, json_encode($data));

    return $data["count"] <= $limit;
}
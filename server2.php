<?php
//php server2.php start
    use Workerman\Worker;
    use Workerman\Lib\Timer;
    require_once __DIR__ . '/vendor/autoload.php';
 
    
    
    function generateRandomNumberJsonMessage($maxRandNum) {
        //$GLOBALS['nymmm'] = $GLOBALS['nymmm'] + 1;
		$time = date('h:i:s');
		$num=rand(0, intval($maxRandNum));  
		 
		$obj = new stdClass();
		$obj->msg = $GLOBALS['array'];
        //$obj->msg = "The server time is: {$time}" . $GLOBALS['num_cli'];
		$obj->num = "$num";
		return json_encode($obj);
	}
    

    // SSL context.
    $context = [
        'ssl' => [
            'local_cert'  => '/home/xkrizanovsky/ssl/webte_fei_stuba_sk.pem',
            'local_pk'    => '/home/xkrizanovsky/ssl/webte.fei.stuba.sk.key',
            'verify_peer' => false,
        ]
    ];
    
    // Create A Worker and Listens 9000 port, use Websocket protocol
    $ws_worker = new Worker("websocket://0.0.0.0:9000", $context);
    
    // Enable SSL. WebSocket+SSL means that Secure WebSocket (wss://). 
    // The similar approaches for Https etc.
    $ws_worker->transport = 'ssl';
 
    // 4 processes
    $ws_worker->count = 1;

    $GLOBALS['array'] = array(
        "userOneY"    => 200,
        "userTwoY"    => 200,
        "ballX"    => 51,
        "ballY"    => 237,
        "userThreeX"    => 200,
        "userFourX"    => 200,
        "gameStarted"    => 0,
        "numberOfPlayers"    => 0,
        "ballDirectionX"    => 8,
        "ballDirectionY"    => 2,
        "ballSpeed"    => 1,
        "userOneLives"    => 3,
        "userTwoLives"    => 3,
        "userThreeLives"    => 3,
        "userFourLives"    => 3,
        "userOneActive"    => 0,
        "userTwoActive"    => 0,
        "userThreeActive"    => 0,
        "userFourActive"    => 0,
        "whoStartsGame"    => 1,
        "numberOfBounces"    => 0,
        "gameFinished"    => 0,
    ); 
    
    
    // Add a Timer to Every worker process when the worker process start
    $ws_worker->onWorkerStart = function($ws_worker)
    {   
        //$GLOBALS['num_cli']=1;


        
        // Timer every 5 seconds
        Timer::add(0.05, function()use($ws_worker)
        {          
            if ($GLOBALS['array']["gameStarted"] == 1) {

                $GLOBALS['array']["ballSpeed"] = $GLOBALS['array']["ballSpeed"]+0.0015;
                $ballX = $GLOBALS['array']["ballX"];
                $ballY = $GLOBALS['array']["ballY"];
                $ballDirectionX = $GLOBALS['array']["ballDirectionX"];
                $ballDirectionY = $GLOBALS['array']["ballDirectionY"];
                $userOneY = $GLOBALS['array']["userOneY"];
                $userTwoY = $GLOBALS['array']["userTwoY"];
                $userThreeX = $GLOBALS['array']["userThreeX"];
                $userFourX = $GLOBALS['array']["userFourX"];
                $ballSpeed = $GLOBALS['array']["ballSpeed"];
 
                
                
                //just moving
                if ($ballX > 50 && $ballX < 425 && $ballY > 50 && $ballY < 425) {
                    $GLOBALS['array']["ballX"] = $ballX + ($ballDirectionX*$ballSpeed);
                    $GLOBALS['array']["ballY"] = $ballY + ($ballDirectionY*$ballSpeed);

                //hit something
                }else if (!($ballX > 50 && $ballX < 425 && $ballY > 50 && $ballY < 425)){

                    //randomness
                    if ($GLOBALS['array']["ballDirectionX"] >= 0) {
                        $GLOBALS['array']["ballDirectionX"] = $GLOBALS['array']["ballDirectionX"] + rand(0.1, 1.2);
                    } else if ($GLOBALS['array']["ballDirectionX"] < 0) {
                        $GLOBALS['array']["ballDirectionX"] = $GLOBALS['array']["ballDirectionX"] - rand(0.1, 1.2);
                    }
                    if ($GLOBALS['array']["ballDirectionY"] >= 0) {
                        $GLOBALS['array']["ballDirectionY"] = $GLOBALS['array']["ballDirectionY"] + rand(0.1, 1.2);
                    } else if ($GLOBALS['array']["ballDirectionY"] < 0) {
                        $GLOBALS['array']["ballDirectionY"] = $GLOBALS['array']["ballDirectionY"] - rand(0.1, 1.2);
                    }




                    $GLOBALS['array']["numberOfBounces"] = $GLOBALS['array']["numberOfBounces"]+1;
                    //hit left wall
                    if ($ballX <= 50) {
                        if ($GLOBALS['array']["userOneActive"] == 1) {
                            if(($ballY+25 > $userOneY && $ballY < $userOneY+100)|| $ballY < 100 || $ballY > 400) {
                                //reverse X
                                $GLOBALS['array']["ballDirectionX"] = $GLOBALS['array']["ballDirectionX"]*(-1);
                            } else {
                                $GLOBALS['array']["gameStarted"] = 0;
                                $GLOBALS['array']["userOneLives"] = $GLOBALS['array']["userOneLives"]-1;
                                $GLOBALS['array']["userOneY"] = 200;
                                $GLOBALS['array']["userTwoY"] = 200;
                                $GLOBALS['array']["ballX"] = 51;
                                $GLOBALS['array']["ballY"] = 237;
                                $GLOBALS['array']["userThreeX"] = 200;
                                $GLOBALS['array']["userFourX"] = 200;
                                $GLOBALS['array']["ballDirectionX"] = 8;
                                $GLOBALS['array']["ballDirectionY"] = 2;
                                $GLOBALS['array']["ballSpeed"] = 1;
                            }
                        } else {
                            //reverse X
                            $GLOBALS['array']["ballDirectionX"] = $GLOBALS['array']["ballDirectionX"]*(-1);
                        }
                    
                    }

                    //hit right wall
                    if ($ballX >= 425) {
                        if ($GLOBALS['array']["userTwoActive"] == 1) {
                            if(($ballY+25 > $userTwoY && $ballY < $userTwoY+100)|| $ballY < 100 || $ballY > 400) {
                                //reverse X
                                $GLOBALS['array']["ballDirectionX"] = $GLOBALS['array']["ballDirectionX"]*(-1);
                            } else {
                                $GLOBALS['array']["gameStarted"] = 0;
                                $GLOBALS['array']["userTwoLives"] = $GLOBALS['array']["userTwoLives"]-1;
                                $GLOBALS['array']["userOneY"] = 200;
                                $GLOBALS['array']["userTwoY"] = 200;
                                $GLOBALS['array']["ballX"] = 51;
                                $GLOBALS['array']["ballY"] = 237;
                                $GLOBALS['array']["userThreeX"] = 200;
                                $GLOBALS['array']["userFourX"] = 200;
                                $GLOBALS['array']["ballDirectionX"] = 8;
                                $GLOBALS['array']["ballDirectionY"] = 2;
                                $GLOBALS['array']["ballSpeed"] = 1;
                            }
                        } else {
                            //reverse X
                            $GLOBALS['array']["ballDirectionX"] = $GLOBALS['array']["ballDirectionX"]*(-1);
                        }
                    }

                    //hit top wall
                    if ($ballY <= 50) {
                        if ($GLOBALS['array']["userThreeActive"] == 1) {
                            if(($ballX+25 > $userThreeX && $ballX < $userThreeX+100)|| $ballX < 100 || $ballX > 400) {
                                //reverse Y
                                $GLOBALS['array']["ballDirectionY"] = $GLOBALS['array']["ballDirectionY"]*(-1);
                            } else {
                                $GLOBALS['array']["gameStarted"] = 0;
                                $GLOBALS['array']["userThreeLives"] = $GLOBALS['array']["userThreeLives"]-1;
                                $GLOBALS['array']["userOneY"] = 200;
                                $GLOBALS['array']["userTwoY"] = 200;
                                $GLOBALS['array']["ballX"] = 51;
                                $GLOBALS['array']["ballY"] = 237;
                                $GLOBALS['array']["userThreeX"] = 200;
                                $GLOBALS['array']["userFourX"] = 200;
                                $GLOBALS['array']["ballDirectionX"] = 8;
                                $GLOBALS['array']["ballDirectionY"] = 2;
                                $GLOBALS['array']["ballSpeed"] = 1;
                            }
                        } else {
                            //reverse Y
                            $GLOBALS['array']["ballDirectionY"] = $GLOBALS['array']["ballDirectionY"]*(-1);
                        }
                    }

                    //hit bottom wall
                    if ($ballY >= 425){
                        if ($GLOBALS['array']["userFourActive"] == 1) {
                            if(($ballX+25 > $userFourX && $ballX < $userFourX+100)|| $ballX < 100 || $ballX > 400) {
                                //reverse Y
                                $GLOBALS['array']["ballDirectionY"] = $GLOBALS['array']["ballDirectionY"]*(-1);
                            } else {
                                $GLOBALS['array']["gameStarted"] = 0;
                                $GLOBALS['array']["userFourLives"] = $GLOBALS['array']["userFourLives"]-1;
                                $GLOBALS['array']["userOneY"] = 200;
                                $GLOBALS['array']["userTwoY"] = 200;
                                $GLOBALS['array']["ballX"] = 51;
                                $GLOBALS['array']["ballY"] = 237;
                                $GLOBALS['array']["userThreeX"] = 200;
                                $GLOBALS['array']["userFourX"] = 200;
                                $GLOBALS['array']["ballDirectionX"] = 8;
                                $GLOBALS['array']["ballDirectionY"] = 2;
                                $GLOBALS['array']["ballSpeed"] = 1;
                            }
                        } else {
                            //reverse Y
                            $GLOBALS['array']["ballDirectionY"] = $GLOBALS['array']["ballDirectionY"]*(-1);
                        }
                    }

                    //make sure it doesnt get stuck after hit
                    if ($GLOBALS['array']["gameStarted"] == 1) {
                        $GLOBALS['array']["ballX"] = $ballX + ($GLOBALS['array']["ballDirectionX"]*$ballSpeed*1.5);
                        $GLOBALS['array']["ballY"] = $ballY + ($GLOBALS['array']["ballDirectionY"]*$ballSpeed*1.5);
                    }
                }

                

                
                //player elimination
                if($GLOBALS['array']["userOneLives"] < 1) {
                    $GLOBALS['array']["userOneActive"] = 0;
                }
                if($GLOBALS['array']["userTwoLives"] < 1) {
                    $GLOBALS['array']["userTwoActive"] = 0;
                }
                if($GLOBALS['array']["userThreeLives"] < 1) {
                    $GLOBALS['array']["userThreeActive"] = 0;
                }
                if($GLOBALS['array']["userFourLives"] < 1) {
                    $GLOBALS['array']["userFourActive"] = 0;
                }

                if($GLOBALS['array']["userOneActive"] == 1) {
                    $GLOBALS['array']["whoStartsGame"] = 1;
                } else if($GLOBALS['array']["userTwoActive"] == 1) {
                    $GLOBALS['array']["whoStartsGame"] = 2;
                } else if($GLOBALS['array']["userThreeActive"] == 1) {
                    $GLOBALS['array']["whoStartsGame"] = 3;
                } else {
                    $GLOBALS['array']["whoStartsGame"] = 4;
                }

                if($GLOBALS['array']["userOneActive"] == 0 && $GLOBALS['array']["userTwoActive"] == 0 && $GLOBALS['array']["userThreeActive"] == 0 && $GLOBALS['array']["userFourActive"] == 0) {
                    $GLOBALS['array']["gameFinished"] = 1;
                }
            
            
            }
            
          // Iterate over connections and send the time          
          foreach($ws_worker->connections as $connection)
            {
                $obj = new stdClass();
                $obj->msg = $GLOBALS['array'];
                $connection->send(json_encode($obj));
            }          
        });
        
    // Emitted when new connection come
    $ws_worker->onConnect = function($connection)
    {
            //$connection->id =  $GLOBALS['array']["allIDs"][0];
            //unset($GLOBALS['array']["allIDs"][0]);
            //$GLOBALS['num_cli'] = $GLOBALS['num_cli'] + 1;
            $GLOBALS['array']["numberOfPlayers"] = $GLOBALS['array']["numberOfPlayers"]+1;

            if($connection->id == 1) {
                $GLOBALS['array']["userOneActive"] = 1;
            } else if($connection->id == 2) {
                $GLOBALS['array']["userTwoActive"] = 1;
            } else if($connection->id == 3) {
                $GLOBALS['array']["userThreeActive"] = 1;
            } else {
                $GLOBALS['array']["userFourActive"] = 1;
            }

            // Emitted when websocket handshake done
            foreach($ws_worker->connections as $connection)
            {
                $obj = new stdClass();
                $obj->msg = $GLOBALS['array'];
                $connection->send(json_encode($obj));
            }  
            $connection->onWebSocketConnect = function($connection)
            {
                echo "New connection\n" . $connection->id;

            };

 
    };
 
    $ws_worker->onMessage = function($connection, $data)
    {
        if ($connection->id == $GLOBALS['array']["whoStartsGame"] && $GLOBALS['array']["gameFinished"] != 1) {
            $GLOBALS['array']["gameStarted"] = 1;
        }
        if ($GLOBALS['array']["gameStarted"] == 1) {
            if($connection->id == 1) {
                if (($data == "ArrowUp" || $data == "ArrowRight") && $GLOBALS['array']["userOneY"] > 100)  {
                    $GLOBALS['array']["userOneY"] = $GLOBALS['array']["userOneY"]-10;   
                } else if (($data == "ArrowDown" || $data == "ArrowLeft") && $GLOBALS['array']["userOneY"] < 300) {
                    $GLOBALS['array']["userOneY"] = $GLOBALS['array']["userOneY"]+10;   
                }
                    $obj = new stdClass();
                    $obj->msg = $GLOBALS['array'];
                    $connection->send(json_encode($obj));
                
            } else if($connection->id == 2) {
                if (($data == "ArrowUp" || $data == "ArrowLeft") && $GLOBALS['array']["userTwoY"] > 100)  {
                    $GLOBALS['array']["userTwoY"] = $GLOBALS['array']["userTwoY"]-10;   
                } else if (($data == "ArrowDown" || $data == "ArrowRight") && $GLOBALS['array']["userTwoY"] < 300) {
                    $GLOBALS['array']["userTwoY"] = $GLOBALS['array']["userTwoY"]+10;   
                }
                    $obj = new stdClass();
                    $obj->msg = $GLOBALS['array'];
                    $connection->send(json_encode($obj));
    
            } else if($connection->id == 3) {
                if (($data == "ArrowUp" || $data == "ArrowLeft") && $GLOBALS['array']["userThreeX"] > 100)  {
                    $GLOBALS['array']["userThreeX"] = $GLOBALS['array']["userThreeX"]-10;   
                } else if (($data == "ArrowDown" || $data == "ArrowRight") && $GLOBALS['array']["userThreeX"] < 300) {
                    $GLOBALS['array']["userThreeX"] = $GLOBALS['array']["userThreeX"]+10;   
                }
                    $obj = new stdClass();
                    $obj->msg = $GLOBALS['array'];
                    $connection->send(json_encode($obj));
    
            } else {
                if (($data == "ArrowUp" || $data == "ArrowLeft") && $GLOBALS['array']["userFourX"] > 100)  {
                    $GLOBALS['array']["userFourX"] = $GLOBALS['array']["userFourX"]-10;   
                } else if (($data == "ArrowDown" || $data == "ArrowRight") && $GLOBALS['array']["userFourX"] < 300) {
                    $GLOBALS['array']["userFourX"] = $GLOBALS['array']["userFourX"]+10;   
                }
                    $obj = new stdClass();
                    $obj->msg = $GLOBALS['array'];
                    $connection->send(json_encode($obj));
            }  
        }
    };
 
    // Emitted when connection closed
    $ws_worker->onClose = function($connection)
    {
        echo "Connection closed";

            //$connection->id =  $GLOBALS['array']["allIDs"][0];
            //unset($GLOBALS['array']["allIDs"][0]);
            //$GLOBALS['num_cli'] = $GLOBALS['num_cli'] + 1;
            $GLOBALS['array']["numberOfPlayers"] = $GLOBALS['array']["numberOfPlayers"]-1;

            if($connection->id == 1) {
                $GLOBALS['array']["userOneActive"] = 0;
            } else if($connection->id == 2) {
                $GLOBALS['array']["userTwoActive"] = 0;
            } else if($connection->id == 3) {
                $GLOBALS['array']["userThreeActive"] = 0;
            } else {
                $GLOBALS['array']["userFourActive"] = 0;
            }

            if($GLOBALS['array']["userOneActive"] == 1) {
                $GLOBALS['array']["whoStartsGame"] = 1;
            } else if($GLOBALS['array']["userTwoActive"] == 1) {
                $GLOBALS['array']["whoStartsGame"] = 2;
            } else if($GLOBALS['array']["userThreeActive"] == 1) {
                $GLOBALS['array']["whoStartsGame"] = 3;
            } else {
                $GLOBALS['array']["whoStartsGame"] = 4;
            }


            // Emitted when websocket handshake done
            foreach($ws_worker->connections as $connection)
            {
                $obj = new stdClass();
                $obj->msg = $GLOBALS['array'];
                $connection->send(json_encode($obj));
            }  
            $connection->onWebSocketConnect = function($connection)
            {
                echo "New connection\n" . $connection->id;

            };
    };
}; 
    // Run worker
    Worker::runAll();
    
    
?>
    
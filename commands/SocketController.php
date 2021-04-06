<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use Workerman\Worker;
use Workerman\WebServer;
use Workerman\Autoloader;
use PHPSocketIO\SocketIO;
use app\components\MyWorker;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;
use Workerman\Connection\TcpConnection;
use app\models\Chatline;

class SocketController extends Controller
{

    public function actionInit()
    {
        $this->startIo();
        $this->startWeb();
    }

    public function actionTest($param)
    {
        echo 'its working ' . $param . ' ' . PHP_EOL;
    }

    public function actionStartIo()
    {
        $this->startIo();
    }
    protected function startIo()
    {
        $io = new SocketIO(2020);
        $io->on('connection', function ($socket) {
            $socket->addedUser = false;

            //when authorized user has opened a site he joins room of his auth_key
            $socket->on('user_joined', function ($user) use ($socket) {
                $socket->join('user_' . $user['id']);
                //$socket->user_key=$user['key'];
                $socket->user_id = $user['id'];
                $socket->broadcast->emit('user joined', $socket->user_id);
                /* $db = Yii::$app->db;
                $db->createCommand("UPDATE `user` SET is_online=1 WHERE id='{$user['id']}'")->execute();
                $db->close();
                $db = NULL; */
                gc_collect_cycles();
                //Yii::$app->db->createCommand()->insert('test',['title'=>serialize($user)])->execute();
            });

            // when the client emits 'new message', this listens and executes
            $socket->on('new message', function ($data) use ($socket) {
                // we tell the client to execute 'new message'
                $socket->to('user_' . $data['receiver_id'])->emit('new message', array(
                    'chat_id' => $data['chat_id'],
                    'message' => $data['message']
                ));
                //$this->saveChatline($data,$socket->user_id);
            });

            // when the client emits 'typing', we broadcast it to others
            $socket->on('typing', function ($data) use ($socket) {
                $socket->to('user_' . $data['receiver_id'])->emit('typing', array(
                    'chat_id' => $data['chat_id']
                ));
            });

            // when the client emits 'stop typing', we broadcast it to others
            $socket->on('stop typing', function ($data) use ($socket) {
                $socket->to('user_' . $data['receiver_id'])->emit('stop typing', array(
                    'chat_id' => $data['chat_id']
                ));
            });

            // when the user disconnects.. perform this
            $socket->on('disconnect', function () use ($socket) {
                $socket->broadcast->emit('user left', $socket->user_id);
                /* $db = Yii::$app->db;
                $db->createCommand("UPDATE `user` SET is_online=0 WHERE id='{$socket->user_id}'")->execute();
                $db->close();
                $db = NULL; */
                gc_collect_cycles();
                //Yii::$app->db->createCommand()->insert('test',['title'=>'left yoba','number'=>$socket->user_id])->execute();
            });
        });

        if (!defined('GLOBAL_START')) {
            MyWorker::runAll();
        }
    }

    public function actionStartWeb()
    {
        $this->startWeb();
    }
    protected function startWeb()
    {
        $web = new Worker('http://0.0.0.0:2022');
        $web->name = 'web';
        define('WEBROOT', __DIR__ . DIRECTORY_SEPARATOR .  'public');

        $web->onMessage = function (TcpConnection $connection, Request $request) {
            $path = $request->path();
            if ($path === '/') {
                $connection->send(exec_php_file(WEBROOT . '/index.html'));
                return;
            }
            $file = realpath(WEBROOT . $path);
            if (false === $file) {
                $connection->send(new Response(404, array(), '<h3>404 Not Found</h3>'));
                return;
            }
            // Security check! Very important!!!
            if (strpos($file, WEBROOT) !== 0) {
                $connection->send(new Response(400));
                return;
            }
            if (\pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $connection->send(exec_php_file($file));
                return;
            }

            $if_modified_since = $request->header('if-modified-since');
            if (!empty($if_modified_since)) {
                // Check 304.
                $info = \stat($file);
                $modified_time = $info ? \date('D, d M Y H:i:s', $info['mtime']) . ' ' . \date_default_timezone_get() : '';
                if ($modified_time === $if_modified_since) {
                    $connection->send(new Response(304));
                    return;
                }
            }
            $connection->send((new Response())->withFile($file));
        };

        function exec_php_file($file)
        {
            \ob_start();
            // Try to include php file.
            try {
                include $file;
            } catch (\Exception $e) {
                echo $e;
            }
            return \ob_get_clean();
        }

        if (!defined('GLOBAL_START')) {
            MyWorker::runAll();
        }
    }

    /* protected function saveChatline($data,$user_id){
        $dao=Yii::$app->db;
        $dao->createCommand()->insert('test', [
            'title' => 'chat line tryin to save',
        ])->execute();
        $line= new Chatline();
        $line->chat_id = $data['chat_id'];
        $line->sender_id = $user_id;
        $line->receiver_id =$data['receiver_id'];
        $line->is_read = 0;
        $line->sent_at = time();
        $line->text = $data['message'];
        $res=$line->save();
        if($res){
            $dao->createCommand()->insert('test', [
                'title' => 'chat line tipa saved',
            ])->execute();
        }else{
            $dao->createCommand()->insert('test', [
                'title' => 'chat line not saved '.serialize($line->getErrors()),
                'number' => 30,
            ])->execute();
        }
    }*/
}

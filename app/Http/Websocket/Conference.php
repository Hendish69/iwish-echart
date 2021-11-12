<?php
namespace App\Http\Websocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Managers\SocketManager;
use App\Models\User;
use App\Models\Api\TxCdmChat;
use App\Models\Api\TxCdmUser;
use App\Models\Api\TxCdm;
use SplObjectStorage;

class Conference implements MessageComponentInterface
{
	private $connections;

	public function __construct()
	{
		$this->connections = new SplObjectStorage();
	}

	public function onOpen(ConnectionInterface $connection)
	{
		dump($connection->resourceId.' connected');
	}

	public function onMessage(ConnectionInterface $connection, $payload)
	{
		$payload = json_decode($payload);

		if (!property_exists($payload, 'type')) {
			$connection->send('ERROR');
		}

		$type = $payload->type;
		$data = $payload->data;

		switch ($payload->type) {
			case 'authorize': 
				$user = User::where('api_token', $data)->first();
				
				$this->connections->detach($connection);

				$connection->user = $user;

				$this->connections->attach($connection);

				dump($connection->resourceId.' identified as '.$user->name);
				$this->debug($connection, 'authorized as '.$user->name);
			break;	

			case 'webauthorize':
				$user = User::where('name', $data)->first();
				
				$this->connections->detach($connection);

				$connection->user = $user;

				$this->connections->attach($connection);

				dump($connection->resourceId.' identified as '.$user->name);
				$this->debug($connection, 'authorized as '.$user->name);
			break;

			case 'chat':
				$sender = null;

				foreach ($this->connections as $conn) {
					if ($conn->resourceId == $connection->resourceId) {
						$sender = $conn;
					}
				}

				$chat = TxCdmChat::with('user.organization')->find($data);
				$cdm = TxCdm::find($chat->cdm_id);
				$users = TxCdmUser::where('cdm_id', $cdm->cdm_id)->get()->pluck('user_id')->toArray();

				foreach ($this->connections as $conn) {
					if ($conn->resourceId == $connection->resourceId) {
						continue;
					}

					if (in_array($sender->user->user_id, $users)) {
						$conn->send($this->createPayload('chat', $chat));
						dump($sender->user->name.' send chat');
					}
				}
			break;
		}
	}

	public function debug(ConnectionInterface $connection, string $message) 
	{
		$connection->send($this->createPayload('debug', $message));
	}

	public function onClose(ConnectionInterface $connection)
	{

	}

	public function onError(ConnectionInterface $connection, Exception $e)
	{
		dd($e->getMessage());
	}

	public function createPayload(string $type, $data)
	{
		$obj = (object) [];

		$obj->type = $type;
		$obj->data = $data;

		return json_encode($obj);
	}
}
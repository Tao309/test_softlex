<?php


/**
 * Class User
 *
 * @property DB $db
 * @property int $id
 * @property string $name
 * @property User $boss
 */
class User
{
	use UserObserver;
	protected $db;

	protected $id;
	protected $name;
	protected $boss;

	public function __construct()
	{
		$this->db = new DB();
	}

	/**
	 * @param int $id
	 * @return User[]
	 */
	public static function getUsersByBossId(int $id):array
	{
		//Оставил инициализацию модели тут, для подхвата
		$model = new User();

		$model->findUsers(['boss_id' => $id]);
	}
}

trait UserObserver
{
	protected function findUsers($params):array
	{
		$query = '
		SELECT id, name
		FROM users
		WHERE
		';

		$w = [];
		foreach($params as $index => $value)
		{
			$w[] = $index.' = '.$value;
		}
		$query .= implode(' AND ', $w);

		//Абстрактный запрос в БД
		$rows =  $this->db->findAll($query);

		$users = [];

		foreach($rows as $row)
		{
			$users[] = (new User())->initModel($row);
		}

		return $users;
	}

	private function initModel(array $data)
	{
		$this->id = $data['id'] ?? 0;
		$this->name = $data['name'] ?? null;
		$this->boss = $data['name'] ?? null;

		return $this;
	}
}

$users = User::getUsersByBossId(5);
foreach($users as $user)
{
	echo $user->name;
}

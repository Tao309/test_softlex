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
	use UserRepository, UserBuilder;

	protected $db;

	protected $id;
	protected $name;
	protected $boss;

	public function __construct()
	{
		$this->db = new DB();
	}

	public function __toString()
	{
		return $this->name;
	}

	/**
	 * @param int $id
	 * @return User[]
	 */
	public static function getUsersByBossId(int $id): array
	{
		//Оставил инициализацию модели тут, для подхвата
		$model = new User();

		return $model->findUsers(['boss_id' => $id]);
	}
}

trait UserRepository
{
	/**
	 * @param $params
	 * @return array
	 */
	protected function findUsers($params): array
	{
		/*
		 * Абстрактный запрос в БД
		 * Без проверки переменных и bindParam
		 */
		$query = '
		SELECT users.id, users.name,
		boss.id AS `bossId`, boss.name AS `bossName`
		FROM users
		LEFT JOIN users AS boss ON (boss.boss_id = users.id)
		WHERE
		';
		$w = [];
		foreach ($params as $index => $value) {
			$w[] = $index . ' = ' . $value;
		}
		$query .= implode(' AND ', $w);

		$rows = $this->db->findAll($query);

		$users = [];

		foreach ($rows as $row) {
			$users[] = (new User())->initModel($row);
		}

		return $users;
	}
}

trait UserBuilder
{
	/**
	 * @param array $data
	 * @return $this
	 */
	protected function initModel(array $data)
	{
		$this->id = $data['id'] ?? 0;
		$this->name = $data['name'] ?? null;
		$this->boss = null;

		if(!empty($data['bossId']))
		{
			$bossData = [
				'id' => $data['bossId'],
				'name' => $data['bossName'],
			];
			$this->boss = (new User)->initModel($bossData);
		}

		return $this;
	}
}

$users = User::getUsersByBossId(5);
foreach ($users as $user) {
	echo 'Пользователь: '.$user;
	if(!empty($user->boss))
	{
		echo 'Его босс: '.$user->boss;
	}
}

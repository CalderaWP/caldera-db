<?php


namespace calderawp\DB;


class Time
{

	const FORMAT = 'Y-m-d H:i:s';
	/**
	 *
	 * @see https://stackoverflow.com/a/5367048/1469799
	 * @param string $mysqldate
	 * @return  string;
	 */
	public static function normalizeMysql(string $mysqldate):string
	{
		$phpdate = strtotime( $mysqldate );
		return date( self::FORMAT, $phpdate );
	}

	/**
	 * @param string $mysqldate
	 *
	 * @return \DateTimeInterface
	 * @throws \Exception
	 */
	public static function dateTimeFromMysql(string $mysqldate) : \DateTimeInterface
	{
		return new \DateTimeImmutable(self::normalizeMysql($mysqldate));
	}
}

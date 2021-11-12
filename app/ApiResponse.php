<?php
namespace App;

use Illuminate\Support\MessageBag;

class ApiResponse
{
	const SUCCESS = 'success';
	const FAIL = 'fail';
	const ERROR = 'error';

	const RESOURCE_NOT_FOUND = 'resource_not_found';

	public static function success($data = null)
	{
		$payload = [
			'status' => self::SUCCESS,
			'data' => $data,
		];

		return response()->json($payload);
	}

	public static function fail(MessageBag $data)
	{
		$results = [];

		foreach ($data->toArray() as $field => $errors) {
			$results[$field] = $errors[0];
		}

		$payload = [
			'status' => self::FAIL,
			'data' => $results
		];

		return response()->json($payload);
	}

	public static function error(string $code, ?string $message = null, $data = null)
	{
		$payload = [
			'status' => self::ERROR,
			'code' => $code,
		];

		if (null !== $message) {
			$payload['message'] = $message;
		}

		if (null !== $data) {
			$payload['data'] = $data;
		}

		return response()->json($payload);
	}
}
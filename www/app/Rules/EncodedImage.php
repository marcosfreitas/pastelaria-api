<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class EncodedImage implements Rule
{
    /**
	 * Array of supporting parameters.
	 *
	 **/
	protected $parameters;

    protected $file;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Set the class properties
		$this->parameters = func_get_args();
    }

    /**
     * Determine if the validation rule passes.
     *
     * The rule requires a single parameter, which is
	 * the expected mime type of the file e.g. png, jpeg etc.
	 *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
	{
		if (is_array($this->parameters[0])) {
			$passes = false;
			foreach ($this->parameters[0] as $mimeType) {
				if (Str::startsWith($value, "data:image/{$mimeType};base64,")) {
					$passes = true;
				}
			}
			return $passes;

		} else {
			if (!Str::startsWith($value, "data:image/{$this->parameters[0]};base64,")) {
				return false;
			}
		}

		$result = validator(['file' => $this->createTemporaryFile($value)], ['file' => 'image'])
			->passes();

		fclose($this->file);

		return $result;
    }

    /**
	 * Write the given data to a temporary file.
	 *
	 * @param string $data .
	 * @return UploadedFile.
	 *
	 **/
	protected function createTemporaryFile($data)
	{
		$this->file = tmpfile();

		fwrite($this->file, base64_decode(Str::after($data, 'base64,')));

		return new UploadedFile(
			stream_get_meta_data($this->file)['uri'], 'image',
			'text/plain', null, null, true
		);
	}

    /**
	 * Get the validation error message.
	 *
	 * @param none.
	 * @return string.
	 *
	 **/
	public function message()
	{
		if (is_array($this->parameters[0])) {
			$validTypes = implode(' OR ', $this->parameters[0]);
			return $this->getLocalizedErrorMessage(
				'encoded_image',
				"The :attribute must be a valid {$validTypes} image"
			);
		} else {
			return $this->getLocalizedErrorMessage(
				'encoded_image',
				"The :attribute must be a valid {$this->parameters[0]} image"
			);
		}
	}

	/**
	 * Retrieve the appropriate, localized validation message
	 * or fall back to the given default.
	 *
	 * @param string $key .
	 * @param string $default .
	 * @return string.
	 *
	 **/
	public static function getLocalizedErrorMessage($key, $default)
	{
		return trans("validation.$key") === $key ? $default : trans("validation.$key");
	}
}

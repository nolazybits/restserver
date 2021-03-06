<?php
namespace Rest\Responses;
/**
* A formatted result object returned to the client from a REST request
*/
class ResponseData
{
    /**
     * HTTP status code to indicate what type of REST result we have
     * @var string
     */
    public $code;

    /**
     * Data object to be parsed out.
     * If an error, this will provide more details about the error that occurred, if available.
     * @var mixed
     */
	public $data;

    /**
     *
     */
    public function __construct()
	{
		$this->clear();
	}

    /**
     *
     */
    public function clear()
    {
        $this->code = \Rest\Http\StatusCodes::SUCCESS_OK;
        $this->data = null;
    }
}
?>
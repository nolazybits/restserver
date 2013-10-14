<?php
namespace Rest;

/**
  * Class RestView
  * Interface describe a View for rendering an Response
  */
interface View
    extends Action
{
    /**
     * Render this view
     * @param \Rest\Server $restServer
     * @return \Rest\Server
     */
	function execute(\Rest\Server $restServer) ;
}
?>

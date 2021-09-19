<?php
declare(strict_types=1);
namespace PhpClamav\Routing;
use PhpClamav\Models\Request;

class Route
{
   private \PhpClamav\Request $request;

   public function __construct(Request $request)
   {
       $this->request = $request;
   }
}
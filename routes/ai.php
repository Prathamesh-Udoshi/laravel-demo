<?php

use App\Mcp\Servers\HelloServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::local('hello-server', HelloServer::class);


<?php

declare(strict_types=1);

namespace BladeUI\Icons\Http\Controllers;

use BladeUI\Icons\Exceptions\SvgNotFound;
use BladeUI\Icons\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class IconsController extends Controller
{
    public function __invoke(Request $request, string $set, string $name)
    {
        $factory = app(Factory::class);
        $prefix = Arr::get($factory->all(), $set.'.prefix');

        try {
            $contents = app(Factory::class)->svg($prefix.'-'.$name, '', [
                'defer' => false,
            ])->contents();
        } catch (SvgNotFound $exception) {
            throw new NotFoundHttpException();
        }

        $contentLength = (string) strlen($contents);
        $cacheControl = 'max-age=31536000, public';
        $expires = date_create('+1 years')->format('D, d M Y H:i:s').' GMT';

        return Response::make(
            content: $contents,
            headers: [
                'Content-Type' => 'image/svg+xml',
                'Content-Length' => $contentLength,
                'Cache-Control' => $cacheControl,
                'Expires' => $expires,
            ]
        );
    }
}

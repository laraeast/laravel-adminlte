<?php

namespace Laraeast\AdminLte\Preset;

use Illuminate\Filesystem\Filesystem;

class AdminLte
{
    /**
     * Install the preset.
     *
     * @return void
     */
    public static function install()
    {
        static::updatePackages();
        static::updateSassAndJs();
        static::updateLayout();
        static::updateWebpack();
        static::removeNodeModules();
    }

    /**
     * Update the given package array.
     *
     * @param  array  $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages)
    {
        return [
                'admin-lte' => '^3.0.0',
                'icomoon' => '^1.0.0',
                'webpack-rtl-plugin' => '^2.0.0',
            ] + $packages;
    }

    /**
     * Update the given scripts array.
     *
     * @param  array  $scripts
     * @return array
     */
    protected static function updateScriptsArray(array $scripts)
    {
        return [
                'adminlte:dev' => 'npm run development -- --env.mixfile=webpack.adminlte.mix.js',
                'adminlte:watch' => 'npm run watch -- --env.mixfile=webpack.adminlte.mix.js',
                'adminlte:prod' => 'npm run production -- --env.mixfile=webpack.adminlte.mix.js',
            ] + $scripts;
    }

    /**
     * Update the Sass & js files for the application dashboard.
     *
     * @return void
     */
    protected static function updateSassAndJs()
    {
        static::ensureAdminlteDirectoriesExists();

        copy(__DIR__.'/stubs/_editor.scss', resource_path('sass/adminlte/_editor.scss'));
        copy(__DIR__.'/stubs/_icons.scss', resource_path('sass/adminlte/_icons.scss'));
        copy(__DIR__.'/stubs/_select2.scss', resource_path('sass/adminlte/_select2.scss'));
        copy(__DIR__.'/stubs/_icheck.scss', resource_path('sass/adminlte/_icheck.scss'));
        copy(__DIR__.'/stubs/adminlte.scss', resource_path('sass/adminlte/adminlte.scss'));
        copy(__DIR__.'/stubs/bootstrap.js', resource_path('js/adminlte/bootstrap.js'));
        copy(__DIR__.'/stubs/adminlte.js', resource_path('js/adminlte/adminlte.js'));
    }

    /**
     * Update the webpack file.
     *
     * @return void
     */
    protected static function updateWebpack()
    {
        copy(__DIR__.'/stubs/webpack.adminlte.mix.js', base_path('webpack.adminlte.mix.js'));
    }

    /**
     * @return void
     */
    protected static function updateLayout()
    {
        copy(__DIR__.'/stubs/adminlte/app.blade.php', resource_path('views/layouts/adminlte/app.blade.php'));

        $filesystem = new Filesystem;

        if (! $filesystem->isDirectory($directory = resource_path('views/auth'))) {
            $filesystem->makeDirectory($directory, 0755, true);
            $filesystem->copyDirectory(__DIR__.'/stubs/auth', resource_path('views/auth'));
        }
    }

    /**
     * Ensure the adminlte directories we need exist.
     *
     * @return void
     */
    protected static function ensureAdminlteDirectoriesExists()
    {
        $filesystem = new Filesystem;

        if (! $filesystem->isDirectory($directory = resource_path('js/adminlte'))) {
            $filesystem->makeDirectory($directory, 0755, true);
        }
        if (! $filesystem->isDirectory($directory = resource_path('sass/adminlte'))) {
            $filesystem->makeDirectory($directory, 0755, true);
        }
        if (! $filesystem->isDirectory($directory = resource_path('views/layouts/adminlte'))) {
            $filesystem->makeDirectory($directory, 0755, true);
        }
    }

    /**
     * Update the "package.json" file.
     *
     * @param  bool  $dev
     * @return void
     */
    protected static function updatePackages($dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = static::updatePackageArray(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        $packages['scripts'] = static::updateScriptsArray(
            array_key_exists('scripts', $packages) ? $packages['scripts'] : []
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    /**
     * Remove the installed Node modules.
     *
     * @return void
     */
    protected static function removeNodeModules()
    {
        tap(new Filesystem, function ($files) {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('yarn.lock'));
        });
    }
}

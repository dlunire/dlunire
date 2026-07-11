<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Humo del skeleton: estructura y autoload mínimos sin levantar el servidor.
 */
final class ProjectStructureTest extends TestCase {
    public function test_public_entrypoint_exists(): void {
        $this->assertFileExists(dirname(__DIR__, 2) . '/public/index.php');
    }

    public function test_env_example_documents_multitenant_and_prefix(): void {
        $example = file_get_contents(dirname(__DIR__, 2) . '/.env.type.example');
        $this->assertIsString($example);
        $this->assertStringContainsString('MULTITENANT', $example);
        $this->assertStringContainsString('EN DESARROLLO', $example);
        $this->assertStringContainsString('DL_PREFIX', $example);
        $this->assertStringContainsString('DL_LIFETIME', $example);
        $this->assertStringContainsString('DL_CORS_ORIGINS', $example);
    }

    public function test_boot_clear_route_preserves_dots(): void {
        $method = new \ReflectionMethod(\Boot\Project::class, 'clear_route');
        $method->setAccessible(true);
        $result = $method->invoke(null, '/var/www/my.app/helpers');
        $this->assertStringContainsString('my.app', $result);
        $this->assertStringEndsWith('/*.php', $result);
        $this->assertStringNotContainsString('my' . DIRECTORY_SEPARATOR . 'app', $result);
    }

    public function test_license_is_agpl(): void {
        $root = dirname(__DIR__, 2);
        $this->assertFileExists($root . '/LICENSE');
        $license = file_get_contents($root . '/LICENSE');
        $this->assertIsString($license);
        $this->assertStringContainsString('GNU AFFERO GENERAL PUBLIC LICENSE', $license);

        $composer = json_decode((string) file_get_contents($root . '/composer.json'), true);
        $this->assertIsArray($composer);
        $this->assertSame('AGPL-3.0-or-later', $composer['license'] ?? null);
    }

    public function test_boot_project_class_is_autoloadable(): void {
        $this->assertTrue(class_exists(\Boot\Project::class));
        $this->assertTrue(method_exists(\Boot\Project::class, 'run'));
    }

    public function test_welcome_controller_is_autoloadable(): void {
        $this->assertTrue(class_exists(\DLUnire\Controllers\WelcomeController::class));
    }

    public function test_tutorial_docs_exist(): void {
        $root = dirname(__DIR__, 2) . '/docs/tutorial';
        $this->assertFileExists($root . '/README.md');
        $this->assertFileExists($root . '/01-que-es-dlunire.md');
        $this->assertFileExists($root . '/12-licencia.md');

        $readme = (string) file_get_contents($root . '/README.md');
        $this->assertStringContainsString('AGPL-3.0-or-later', $readme);
    }

    public function test_setup_env_script_is_registered(): void {
        $root = dirname(__DIR__, 2);
        $this->assertFileExists($root . '/bin/setup-env.php');

        $composer = json_decode((string) file_get_contents($root . '/composer.json'), true);
        $this->assertIsArray($composer);
        $scripts = $composer['scripts'] ?? [];
        $this->assertArrayHasKey('post-create-project-cmd', $scripts);
        $this->assertArrayHasKey('setup-env', $scripts);

        $post = $scripts['post-create-project-cmd'];
        $flat = is_array($post) ? implode(' ', $post) : (string) $post;
        $this->assertStringContainsString('bin/setup-env.php', $flat);
    }
}

<?php declare(strict_types=1);

/**
 * File for plugin version tests
 *
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.0.0
 * @version 1.0.0
 */

namespace RichardMuvirimi\WooCustomGateway\Tests;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * Controller Test Cases class
 *
 * @author Richard Muvirimi <richard@tyganeutronics.com>
 * @since 1.5.10
 * @version 1.5.10
 */
class PluginVersionTest extends TestCase
{

    // Adds Mockery expectations to the PHPUnit assertions count.
    use MockeryPHPUnitIntegration;

	/**
	 * Test WooCommerce tested version
	 *
	 * @author Richard Muvirimi <richard@tyganeutronics.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return void
	 */
	public function testWooCommerceVersion():void{

		$response = file_get_contents("https://api.wordpress.org/plugins/info/1.0/woocommerce.json");

		if ($response){
			$data = json_decode($response, true);

			preg_match("/^(\d*\.\d*)/", $data["version"], $matches);

			$latestVersion = $matches[1];
			$currentTxtVersion = $this->getReadMeTxtVariable("WC tested up to");
			$currentMdVersion = $this->getReadMeMdVariable("WC tested up to");
			$pluginVersion = $this->getPluginVariable("WC tested up to");

			self::assertTrue(version_compare($latestVersion, $currentTxtVersion, "="), "readme.txt WC tested up to does not match the latest version : " . $latestVersion);
			self::assertTrue(version_compare($latestVersion, $currentMdVersion, "="), "readme.md WC tested up to does not match the latest version : " . $latestVersion);
			self::assertTrue(version_compare($latestVersion, $pluginVersion, "="), "Plugin file WC tested up to does not match the plugin version : " . $latestVersion);
		}
	}

	/**
	 * Test WordPress tested version
	 *
	 * @author Richard Muvirimi <richard@tyganeutronics.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return void
	 */
	public function testWordPressVersion():void{

		$response = file_get_contents("https://api.wordpress.org/core/version-check/1.7");

		if ($response){
			$data = json_decode($response, true);

			preg_match("/^(\d*\.\d*)/", $data["offers"][0]["current"], $matches);

			$latestVersion = $matches[1];
			$currentTxtVersion = $this->getReadMeTxtVariable("tested up to");
			$currentMdVersion = $this->getReadMeMdVariable("tested up to");

			self::assertTrue(version_compare($latestVersion, $currentTxtVersion, "="), "readme.txt WordPress tested up to version does not match the latest version : " . $latestVersion);
			self::assertTrue(version_compare($latestVersion, $currentMdVersion, "="), "readme.md WordPress tested up to version does not match the latest version : " . $latestVersion);
		}
	}

    /**
     * Test the plugin versions match
     *
     * @return void
     * @version 1.5.10
     * @since 1.5.10
     */
    public function testPluginVersions(): void
    {

		$rmTxtVersion = $this->getReadMeTxtVariable("stable tag");
		$rmMdVersion = $this->getReadMeMdVariable("stable tag");
		$pluginVersion = $this->getPluginVariable("version");

        // assert versions match
        self::assertTrue(version_compare(WOO_CUSTOM_GATEWAY_VERSION, $rmTxtVersion, "="), "readme.txt Plugin version does not match the stable version : " . WOO_CUSTOM_GATEWAY_VERSION);
        self::assertTrue(version_compare(WOO_CUSTOM_GATEWAY_VERSION, $rmMdVersion, "="), "readme.md Plugin version does not match the stable tag version : " . WOO_CUSTOM_GATEWAY_VERSION);
        self::assertTrue(version_compare(WOO_CUSTOM_GATEWAY_VERSION, $pluginVersion, "="), "Plugin version does not match the stable tag version : " . WOO_CUSTOM_GATEWAY_VERSION);
    }

	/**
	 * Get plugin version from the plugin file
	 * 
	 * @param string $name
	 *
	 * @author Richard Muvirimi <richard@tyganeutronics.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return string
	 */
	private function getPluginVariable(string $name):string{

		preg_match("/".$name."\s*:\s*(.*)$/mi", file_get_contents(WOO_CUSTOM_GATEWAY_FILE), $matches);

		list(,$version) = $matches;

		return trim($version);
	}

	/**
	 * Get plugin variable from readme.md
	 * 
	 * @param string $name
	 *
	 * @author Richard Muvirimi <richard@tyganeutronics.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return string
	 */
	private function getReadMeMdVariable(string $name):string{
		list($readme) = glob('[Rr][Ee][Aa][Dd][Mm][Ee].[Mm][Dd]', 0);

		$name = preg_replace("/\s/", "\s", $name);

		preg_match("/".$name."\s?:.+\s(.*)$/miU", file_get_contents(dirname(WOO_CUSTOM_GATEWAY_FILE) . DIRECTORY_SEPARATOR.$readme), $matches);

		list(,$version) = $matches;

		return trim($version);
	}

	/**
	 * Get plugin variable from readme.txt
	 *
	 * @param string $name
	 *
	 * @author Richard Muvirimi <richard@tyganeutronics.com>
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return string
	 */
	private function getReadMeTxtVariable(string $name):string{
		list($readme) = glob('[Rr][Ee][Aa][Dd][Mm][Ee].[Tt][Xx][Tt]', 0);

		$name = preg_replace("/\s/", "\s", $name);

		preg_match("/".$name."\s?:\s?(.*)$/mi", file_get_contents(dirname(WOO_CUSTOM_GATEWAY_FILE) . DIRECTORY_SEPARATOR.$readme), $matches);

		list(,$version) = $matches;

		return trim($version);
	}

	/**
     * Tear Down
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }

    /**
     * SetUp
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }

}
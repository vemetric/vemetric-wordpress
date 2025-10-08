<?php

use PHPUnit\Framework\TestCase;
use Vemetric\Frontend\ScriptTagBuilder;

class ScriptTagBuilderTest extends TestCase {
    /**
     * Test building script attributes with minimal configuration
     */
    public function test_build_attributes_minimal() {
        $token = 'test-token';
        $host = '';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = '';

        $result = ScriptTagBuilder::buildAttributes(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $this->assertEquals(' data-token="test-token"', $result);
    }

    /**
     * Test building script attributes with all options enabled
     */
    public function test_build_attributes_all_enabled() {
        $token = 'test-token';
        $host = 'https://example.com';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = '/path1,/path2';

        $result = ScriptTagBuilder::buildAttributes(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = ' data-token="test-token" data-host="https://example.com" data-mask-paths="[&quot;/path1&quot;,&quot;/path2&quot;]"';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test building script attributes with all tracking options disabled
     */
    public function test_build_attributes_all_disabled() {
        $token = 'test-token';
        $host = 'https://example.com';
        $track_pageviews = false;
        $track_outbound = false;
        $track_data_attributes = false;
        $mask_paths = '';

        $result = ScriptTagBuilder::buildAttributes(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = ' data-token="test-token" data-host="https://example.com" data-track-page-views="false" data-track-outbound-links="false" data-track-data-attributes="false"';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test building script attributes with special characters
     */
    public function test_build_attributes_special_chars() {
        $token = 'test-token"with"quotes';
        $host = 'https://example.com/path?param=value&other=123';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = '';

        $result = ScriptTagBuilder::buildAttributes(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = ' data-token="test-token&quot;with&quot;quotes" data-host="https://example.com/path?param=value&amp;other=123"';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test building script attributes with whitespace in mask paths
     */
    public function test_build_attributes_mask_paths_whitespace() {
        $token = 'test-token';
        $host = '';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = ' /path1 , /path2 ';

        $result = ScriptTagBuilder::buildAttributes(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = ' data-token="test-token" data-mask-paths="[&quot;/path1&quot;,&quot;/path2&quot;]"';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test building script attributes with complex mask paths containing special characters
     */
    public function test_build_attributes_mask_paths_special_chars() {
        $token = 'test-token';
        $host = '';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = '/path/with spaces,/path/with"quotes,/path/with&special';

        $result = ScriptTagBuilder::buildAttributes(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = ' data-token="test-token" data-mask-paths="[&quot;/path/with spaces&quot;,&quot;/path/with&quot;quotes&quot;,&quot;/path/with&amp;special&quot;]"';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test building script attributes with host containing special characters
     */
    public function test_build_attributes_host_special_chars() {
        $token = 'test-token';
        $host = 'https://example.com/path with spaces/script.php?param=value with spaces&other=123';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = '';

        $result = ScriptTagBuilder::buildAttributes(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = ' data-token="test-token" data-host="https://example.com/path with spaces/script.php?param=value with spaces&amp;other=123"';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test building script attributes with mixed configuration
     */
    public function test_build_attributes_mixed_config() {
        $token = 'test-token';
        $host = 'https://example.com';
        $track_pageviews = false;
        $track_outbound = true;
        $track_data_attributes = false;
        $mask_paths = '/path1,/path2';

        $result = ScriptTagBuilder::buildAttributes(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = ' data-token="test-token" data-host="https://example.com" data-track-page-views="false" data-track-data-attributes="false" data-mask-paths="[&quot;/path1&quot;,&quot;/path2&quot;]"';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test building script attributes with empty token
     */
    public function test_build_attributes_empty_token() {
        $token = '';
        $host = '';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = '';

        $result = ScriptTagBuilder::buildAttributes(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = ' data-token=""';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test building script attributes with single mask path
     */
    public function test_build_attributes_single_mask_path() {
        $token = 'test-token';
        $host = '';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = '/single/path';

        $result = ScriptTagBuilder::buildAttributes(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = ' data-token="test-token" data-mask-paths="[&quot;/single/path&quot;]"';
        $this->assertEquals($expected, $result);
    }
    /**
     * Test building options with minimal configuration
     */
    public function test_build_options_minimal() {
        $token = 'test-token';
        $host = '';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = '';

        $result = ScriptTagBuilder::buildOptions(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = [
            'sdk' => 'wordpress',
            'token' => 'test-token',
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Test building options with all options enabled
     */
    public function test_build_options_all_enabled() {
        $token = 'test-token';
        $host = 'https://example.com';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = '/path1,/path2';

        $result = ScriptTagBuilder::buildOptions(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = [
            'sdk' => 'wordpress',
            'token' => 'test-token',
            'host' => 'https://example.com',
            'maskPaths' => ['/path1', '/path2'],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Test building options with all tracking options disabled
     */
    public function test_build_options_all_disabled() {
        $token = 'test-token';
        $host = 'https://example.com';
        $track_pageviews = false;
        $track_outbound = false;
        $track_data_attributes = false;
        $mask_paths = '';

        $result = ScriptTagBuilder::buildOptions(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = [
            'sdk' => 'wordpress',
            'token' => 'test-token',
            'host' => 'https://example.com',
            'trackPageViews' => false,
            'trackOutboundLinks' => false,
            'trackDataAttributes' => false,
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Test building options with URL containing slashes
     */
    public function test_build_options_url_with_slashes() {
        $token = 'test-token';
        $host = 'http://localhost:4123';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = '';

        $result = ScriptTagBuilder::buildOptions(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = [
            'sdk' => 'wordpress',
            'token' => 'test-token',
            'host' => 'http://localhost:4123',
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Test building options with whitespace in mask paths
     */
    public function test_build_options_mask_paths_whitespace() {
        $token = 'test-token';
        $host = '';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = ' /path1 , /path2 ';

        $result = ScriptTagBuilder::buildOptions(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = [
            'sdk' => 'wordpress',
            'token' => 'test-token',
            'maskPaths' => ['/path1', '/path2'],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Test building options with single mask path
     */
    public function test_build_options_single_mask_path() {
        $token = 'test-token';
        $host = '';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = '/single/path';

        $result = ScriptTagBuilder::buildOptions(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = [
            'sdk' => 'wordpress',
            'token' => 'test-token',
            'maskPaths' => ['/single/path'],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Test building options with complex URLs
     */
    public function test_build_options_complex_url() {
        $token = 'test-token';
        $host = 'https://analytics.example.com:8080/api/v1';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = '';

        $result = ScriptTagBuilder::buildOptions(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $expected = [
            'sdk' => 'wordpress',
            'token' => 'test-token',
            'host' => 'https://analytics.example.com:8080/api/v1',
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Test that SDK is always set to wordpress
     */
    public function test_build_options_sdk_always_wordpress() {
        $token = 'test-token';
        $host = '';
        $track_pageviews = true;
        $track_outbound = true;
        $track_data_attributes = true;
        $mask_paths = '';

        $result = ScriptTagBuilder::buildOptions(
            $token,
            $host,
            $track_pageviews,
            $track_outbound,
            $track_data_attributes,
            $mask_paths
        );

        $this->assertArrayHasKey('sdk', $result);
        $this->assertEquals('wordpress', $result['sdk']);
    }
} 
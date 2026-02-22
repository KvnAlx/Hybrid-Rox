<?php
use HybridRox\WorkoutFilters\REST\Controller;

class HybridRox_REST_Query_Mapping_Test extends WP_UnitTestCase {
	public function test_duration_sort_maps_to_meta_query() {
		$args = Controller::build_query_args(
			array(
				'sort'         => 'duration_desc',
				'duration_min' => 20,
				'duration_max' => 40,
			)
		);

		$this->assertSame( 'duration_minutes', $args['meta_key'] );
		$this->assertSame( 'DESC', $args['order'] );
		$this->assertSame( 'BETWEEN', $args['meta_query'][0]['compare'] );
	}
}

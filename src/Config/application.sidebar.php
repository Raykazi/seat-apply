<?PHP 

return [
	'srp' => [
		'name' => 'Ship Replacement Program',
		'icon' => 'fas fa-rocket',
		'route_segment' => 'srp',
		'permission' => 'srp.request',
		'entries' => [
			[
				'name' => 'Apply',
				'icon' => 'fas fa-medkit',
				'route' => 'srp.request',
				'permission' => 'application.apply',
			],
			[
				'name' => 'Applications',
				'icon' => 'fas fa-gavel',
				'route' => 'srpadmin.list',
				'permission' => 'application.recruiter',
			],
//            [
//                'name' => 'Metrics',
//                'icon' => 'fas fa-chart-bar',
//                'route' => 'srp.metrics',
//                'permission' => 'srp.settle',
//			],
//			[
//                'name' => 'Instructions',
//                'icon' => 'fas fa-book-open',
//                'route' => 'srp.instructions',
//                'permission' => 'srp.request',
//			],
			[
                'name' => 'About',
                'icon' => 'fas fa-info',
                'route' => 'srp.about',
                'permission' => 'application.apply',
            ],
		],
	],
];

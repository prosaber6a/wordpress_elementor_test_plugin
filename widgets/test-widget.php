<?php


class Elementor_Test_Widget extends \Elementor\Widget_Base{

	public function get_name() {
		return 'TestWidget';
	}

	public function get_title() {
		return __( 'TestWidget', 'elementortestplugin' );
	}

	public function get_icon() {
		return 'fa fa-code';
	}

	public function get_categories() {
		return [ 'general', 'testCategory' ];
	}


	protected function _register_controls() {
		// Start Content Section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'elementortestplugin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

            $this->add_control(
                'heading',
                [
                    'label' => __( 'Heading', 'elementortestplugin' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => __( 'Hello World', 'elementortestplugin' )
                ]
            );

            $this->add_control(
                'description',
                [
                    'label' => __( 'Description', 'elementortestplugin' ),
                    'type' => \Elementor\Controls_Manager::TEXTAREA
                ]
            );



		$this->end_controls_section();
		// End Content Section


		// Start Image Section

		$this->start_controls_section(
			'image_section',
			[
				'label' => __( 'Image', 'elementortestplugin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


			$this->add_control(
				'imagex',
				[
					'label' => __( 'Image', 'elementortestplugin' ),
					'type' => \Elementor\Controls_Manager::MEDIA,
					'default' => [
						'url' => \Elementor\Utils::get_placeholder_image_src(),
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Image_Size::get_type(),
				[
					'default' => 'large',
					'name' => 'image_size'
				]
			);


		$this->end_controls_section();
		// End Image Section


		// Start Position Section

		$this->start_controls_section(
			'position_section',
			[
				'label' => __( 'Position', 'elementortestplugin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'alignment',
				[
					'label' => __( 'Alignment', 'elementortestplugin' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'left',
					'options' => array(
						"left" => __("Left", "elementortestplugin"),
						"right" => __("Right", "elementortestplugin"),
						"center" => __("Center", "elementortestplugin")
					),
					'selectors' => [
						"{{WRAPPER}} h1.heading" => "text-align:{{VALUE}}"
					]
				]
			);

			$this->add_control(
				'desc_alignment',
				[
					'label' => __( 'Alignment', 'elementortestplugin' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'left',
					'options' => array(
						"left" => __("Left", "elementortestplugin"),
						"right" => __("Right", "elementortestplugin"),
						"center" => __("Center", "elementortestplugin")
					),
					'selectors' => [
						"{{WRAPPER}} p.description" => "text-align:{{VALUE}}"
					]
				]
			);

		$this->end_controls_section();
		// End Position Section



		// Start Color Section

		$this->start_controls_section(
			'color_section',
			[
				'label' => __( 'Color', 'elementortestplugin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


			$this->add_control(
				'heading_color',
				[
					'label' => __( 'Heading Color', 'elementortestplugin' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#000',
					'selectors' => [
						"{{WRAPPER}} h1.heading" => "color : {{VALUE}}"
					]
				]
			);

			$this->add_control(
				'description_color',
				[
					'label' => __( 'Description Color', 'elementortestplugin' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '#000',
					'selectors' => [
						"{{WRAPPER}} p.description" => "color : {{VALUE}}"
					]
				]
			);


		$this->end_controls_section();
		// End Color Section
	}



	protected function render() {

		$settings = $this->get_settings_for_display();
		$heading = $settings['heading'];
		$description = $settings['description'];
//		$image = $settings['imagex']['url'];

		$this->add_inline_editing_attributes("heading", "none");
		$this->add_inline_editing_attributes("description", "none");
		$this->add_render_attribute("heading",
				[
					"class" => "heading"
				]
			);
		$this->add_render_attribute("description",
				[
					"class" => "description"
				]
			);
//		echo "<img src='".$image."'>";
		echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size', 'imagex'  );
		echo "<h1 ".$this->get_render_attribute_string("heading").">".esc_html($heading)."</h1>";
		echo "<p  ".$this->get_render_attribute_string("description").">".wp_kses_post($description)."</p>";


	}


	public function _content_template () {

		?>
		<#
			var image = {
				id: settings.imagex.id,
				url: settings.imagex.url,
				size: settings.image_size_size,
				dimension: settings.image_size_custom_dimension
			};

			var imageUrl = elementor.imagesManager.getImageUrl(image);

			view.addInlineEditingAttributes('heading', 'none');
			view.addRenderAttribute("heading", {"class":"heading"});

			view.addInlineEditingAttributes('description', 'none');
			view.addRenderAttribute("description", {"class":"description"});
		#>


		<img src="{{{ imageUrl }}}"/>
		<h1 {{{ view.getRenderAttributeString('heading') }}}> {{settings.heading}} </h1>
		<p {{{ view.getRenderAttributeString('description') }}}> {{settings.description}} </p>

		<?php
	}




}
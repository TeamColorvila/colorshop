<?php
/**
 * ColorShop countries
 *
 * The ColorShop countries class stores country/state data.
 *
 * @class 		CS_Countries
 * @version		1.0.0
 * @package		ColorShop/Classes
 * @category	Class
 * @author 		ColorVila
 */
class CS_Countries {

	/** @var array Array of countries */
	public $countries;

	/** @var array Array of states */
	public $states;

	/** @var array Array of locales */
	public $locale;

	/** @var array Array of address formats for locales */
	public $address_formats;

	/**
	 * Constructor for the counties class - defines all countries and states.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		global $colorshop, $states;

		$this->countries = apply_filters('colorshop_countries', array(
			'AF' => __( 'Afghanistan', 'colorshop' ),
			'AX' => __( '&#197;land Islands', 'colorshop' ),
			'AL' => __( 'Albania', 'colorshop' ),
			'DZ' => __( 'Algeria', 'colorshop' ),
			'AD' => __( 'Andorra', 'colorshop' ),
			'AO' => __( 'Angola', 'colorshop' ),
			'AI' => __( 'Anguilla', 'colorshop' ),
			'AQ' => __( 'Antarctica', 'colorshop' ),
			'AG' => __( 'Antigua and Barbuda', 'colorshop' ),
			'AR' => __( 'Argentina', 'colorshop' ),
			'AM' => __( 'Armenia', 'colorshop' ),
			'AW' => __( 'Aruba', 'colorshop' ),
			'AU' => __( 'Australia', 'colorshop' ),
			'AT' => __( 'Austria', 'colorshop' ),
			'AZ' => __( 'Azerbaijan', 'colorshop' ),
			'BS' => __( 'Bahamas', 'colorshop' ),
			'BH' => __( 'Bahrain', 'colorshop' ),
			'BD' => __( 'Bangladesh', 'colorshop' ),
			'BB' => __( 'Barbados', 'colorshop' ),
			'BY' => __( 'Belarus', 'colorshop' ),
			'BE' => __( 'Belgium', 'colorshop' ),
			'PW' => __( 'Belau', 'colorshop' ),
			'BZ' => __( 'Belize', 'colorshop' ),
			'BJ' => __( 'Benin', 'colorshop' ),
			'BM' => __( 'Bermuda', 'colorshop' ),
			'BT' => __( 'Bhutan', 'colorshop' ),
			'BO' => __( 'Bolivia', 'colorshop' ),
			'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'colorshop' ),
			'BA' => __( 'Bosnia and Herzegovina', 'colorshop' ),
			'BW' => __( 'Botswana', 'colorshop' ),
			'BV' => __( 'Bouvet Island', 'colorshop' ),
			'BR' => __( 'Brazil', 'colorshop' ),
			'IO' => __( 'British Indian Ocean Territory', 'colorshop' ),
			'VG' => __( 'British Virgin Islands', 'colorshop' ),
			'BN' => __( 'Brunei', 'colorshop' ),
			'BG' => __( 'Bulgaria', 'colorshop' ),
			'BF' => __( 'Burkina Faso', 'colorshop' ),
			'BI' => __( 'Burundi', 'colorshop' ),
			'KH' => __( 'Cambodia', 'colorshop' ),
			'CM' => __( 'Cameroon', 'colorshop' ),
			'CA' => __( 'Canada', 'colorshop' ),
			'CV' => __( 'Cape Verde', 'colorshop' ),
			'KY' => __( 'Cayman Islands', 'colorshop' ),
			'CF' => __( 'Central African Republic', 'colorshop' ),
			'TD' => __( 'Chad', 'colorshop' ),
			'CL' => __( 'Chile', 'colorshop' ),
			'CN' => __( 'China', 'colorshop' ),
			'CX' => __( 'Christmas Island', 'colorshop' ),
			'CC' => __( 'Cocos (Keeling) Islands', 'colorshop' ),
			'CO' => __( 'Colombia', 'colorshop' ),
			'KM' => __( 'Comoros', 'colorshop' ),
			'CG' => __( 'Congo (Brazzaville)', 'colorshop' ),
			'CD' => __( 'Congo (Kinshasa)', 'colorshop' ),
			'CK' => __( 'Cook Islands', 'colorshop' ),
			'CR' => __( 'Costa Rica', 'colorshop' ),
			'HR' => __( 'Croatia', 'colorshop' ),
			'CU' => __( 'Cuba', 'colorshop' ),
			'CW' => __( 'Cura&Ccedil;ao', 'colorshop' ),
			'CY' => __( 'Cyprus', 'colorshop' ),
			'CZ' => __( 'Czech Republic', 'colorshop' ),
			'DK' => __( 'Denmark', 'colorshop' ),
			'DJ' => __( 'Djibouti', 'colorshop' ),
			'DM' => __( 'Dominica', 'colorshop' ),
			'DO' => __( 'Dominican Republic', 'colorshop' ),
			'EC' => __( 'Ecuador', 'colorshop' ),
			'EG' => __( 'Egypt', 'colorshop' ),
			'SV' => __( 'El Salvador', 'colorshop' ),
			'GQ' => __( 'Equatorial Guinea', 'colorshop' ),
			'ER' => __( 'Eritrea', 'colorshop' ),
			'EE' => __( 'Estonia', 'colorshop' ),
			'ET' => __( 'Ethiopia', 'colorshop' ),
			'FK' => __( 'Falkland Islands', 'colorshop' ),
			'FO' => __( 'Faroe Islands', 'colorshop' ),
			'FJ' => __( 'Fiji', 'colorshop' ),
			'FI' => __( 'Finland', 'colorshop' ),
			'FR' => __( 'France', 'colorshop' ),
			'GF' => __( 'French Guiana', 'colorshop' ),
			'PF' => __( 'French Polynesia', 'colorshop' ),
			'TF' => __( 'French Southern Territories', 'colorshop' ),
			'GA' => __( 'Gabon', 'colorshop' ),
			'GM' => __( 'Gambia', 'colorshop' ),
			'GE' => __( 'Georgia', 'colorshop' ),
			'DE' => __( 'Germany', 'colorshop' ),
			'GH' => __( 'Ghana', 'colorshop' ),
			'GI' => __( 'Gibraltar', 'colorshop' ),
			'GR' => __( 'Greece', 'colorshop' ),
			'GL' => __( 'Greenland', 'colorshop' ),
			'GD' => __( 'Grenada', 'colorshop' ),
			'GP' => __( 'Guadeloupe', 'colorshop' ),
			'GT' => __( 'Guatemala', 'colorshop' ),
			'GG' => __( 'Guernsey', 'colorshop' ),
			'GN' => __( 'Guinea', 'colorshop' ),
			'GW' => __( 'Guinea-Bissau', 'colorshop' ),
			'GY' => __( 'Guyana', 'colorshop' ),
			'HT' => __( 'Haiti', 'colorshop' ),
			'HM' => __( 'Heard Island and McDonald Islands', 'colorshop' ),
			'HN' => __( 'Honduras', 'colorshop' ),
			'HK' => __( 'Hong Kong', 'colorshop' ),
			'HU' => __( 'Hungary', 'colorshop' ),
			'IS' => __( 'Iceland', 'colorshop' ),
			'IN' => __( 'India', 'colorshop' ),
			'ID' => __( 'Indonesia', 'colorshop' ),
			'IR' => __( 'Iran', 'colorshop' ),
			'IQ' => __( 'Iraq', 'colorshop' ),
			'IE' => __( 'Republic of Ireland', 'colorshop' ),
			'IM' => __( 'Isle of Man', 'colorshop' ),
			'IL' => __( 'Israel', 'colorshop' ),
			'IT' => __( 'Italy', 'colorshop' ),
			'CI' => __( 'Ivory Coast', 'colorshop' ),
			'JM' => __( 'Jamaica', 'colorshop' ),
			'JP' => __( 'Japan', 'colorshop' ),
			'JE' => __( 'Jersey', 'colorshop' ),
			'JO' => __( 'Jordan', 'colorshop' ),
			'KZ' => __( 'Kazakhstan', 'colorshop' ),
			'KE' => __( 'Kenya', 'colorshop' ),
			'KI' => __( 'Kiribati', 'colorshop' ),
			'KW' => __( 'Kuwait', 'colorshop' ),
			'KG' => __( 'Kyrgyzstan', 'colorshop' ),
			'LA' => __( 'Laos', 'colorshop' ),
			'LV' => __( 'Latvia', 'colorshop' ),
			'LB' => __( 'Lebanon', 'colorshop' ),
			'LS' => __( 'Lesotho', 'colorshop' ),
			'LR' => __( 'Liberia', 'colorshop' ),
			'LY' => __( 'Libya', 'colorshop' ),
			'LI' => __( 'Liechtenstein', 'colorshop' ),
			'LT' => __( 'Lithuania', 'colorshop' ),
			'LU' => __( 'Luxembourg', 'colorshop' ),
			'MO' => __( 'Macao S.A.R., China', 'colorshop' ),
			'MK' => __( 'Macedonia', 'colorshop' ),
			'MG' => __( 'Madagascar', 'colorshop' ),
			'MW' => __( 'Malawi', 'colorshop' ),
			'MY' => __( 'Malaysia', 'colorshop' ),
			'MV' => __( 'Maldives', 'colorshop' ),
			'ML' => __( 'Mali', 'colorshop' ),
			'MT' => __( 'Malta', 'colorshop' ),
			'MH' => __( 'Marshall Islands', 'colorshop' ),
			'MQ' => __( 'Martinique', 'colorshop' ),
			'MR' => __( 'Mauritania', 'colorshop' ),
			'MU' => __( 'Mauritius', 'colorshop' ),
			'YT' => __( 'Mayotte', 'colorshop' ),
			'MX' => __( 'Mexico', 'colorshop' ),
			'FM' => __( 'Micronesia', 'colorshop' ),
			'MD' => __( 'Moldova', 'colorshop' ),
			'MC' => __( 'Monaco', 'colorshop' ),
			'MN' => __( 'Mongolia', 'colorshop' ),
			'ME' => __( 'Montenegro', 'colorshop' ),
			'MS' => __( 'Montserrat', 'colorshop' ),
			'MA' => __( 'Morocco', 'colorshop' ),
			'MZ' => __( 'Mozambique', 'colorshop' ),
			'MM' => __( 'Myanmar', 'colorshop' ),
			'NA' => __( 'Namibia', 'colorshop' ),
			'NR' => __( 'Nauru', 'colorshop' ),
			'NP' => __( 'Nepal', 'colorshop' ),
			'NL' => __( 'Netherlands', 'colorshop' ),
			'AN' => __( 'Netherlands Antilles', 'colorshop' ),
			'NC' => __( 'New Caledonia', 'colorshop' ),
			'NZ' => __( 'New Zealand', 'colorshop' ),
			'NI' => __( 'Nicaragua', 'colorshop' ),
			'NE' => __( 'Niger', 'colorshop' ),
			'NG' => __( 'Nigeria', 'colorshop' ),
			'NU' => __( 'Niue', 'colorshop' ),
			'NF' => __( 'Norfolk Island', 'colorshop' ),
			'KP' => __( 'North Korea', 'colorshop' ),
			'NO' => __( 'Norway', 'colorshop' ),
			'OM' => __( 'Oman', 'colorshop' ),
			'PK' => __( 'Pakistan', 'colorshop' ),
			'PS' => __( 'Palestinian Territory', 'colorshop' ),
			'PA' => __( 'Panama', 'colorshop' ),
			'PG' => __( 'Papua New Guinea', 'colorshop' ),
			'PY' => __( 'Paraguay', 'colorshop' ),
			'PE' => __( 'Peru', 'colorshop' ),
			'PH' => __( 'Philippines', 'colorshop' ),
			'PN' => __( 'Pitcairn', 'colorshop' ),
			'PL' => __( 'Poland', 'colorshop' ),
			'PT' => __( 'Portugal', 'colorshop' ),
			'QA' => __( 'Qatar', 'colorshop' ),
			'RE' => __( 'Reunion', 'colorshop' ),
			'RO' => __( 'Romania', 'colorshop' ),
			'RU' => __( 'Russia', 'colorshop' ),
			'RW' => __( 'Rwanda', 'colorshop' ),
			'BL' => __( 'Saint Barth&eacute;lemy', 'colorshop' ),
			'SH' => __( 'Saint Helena', 'colorshop' ),
			'KN' => __( 'Saint Kitts and Nevis', 'colorshop' ),
			'LC' => __( 'Saint Lucia', 'colorshop' ),
			'MF' => __( 'Saint Martin (French part)', 'colorshop' ),
			'SX' => __( 'Saint Martin (Dutch part)', 'colorshop' ),
			'PM' => __( 'Saint Pierre and Miquelon', 'colorshop' ),
			'VC' => __( 'Saint Vincent and the Grenadines', 'colorshop' ),
			'SM' => __( 'San Marino', 'colorshop' ),
			'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'colorshop' ),
			'SA' => __( 'Saudi Arabia', 'colorshop' ),
			'SN' => __( 'Senegal', 'colorshop' ),
			'RS' => __( 'Serbia', 'colorshop' ),
			'SC' => __( 'Seychelles', 'colorshop' ),
			'SL' => __( 'Sierra Leone', 'colorshop' ),
			'SG' => __( 'Singapore', 'colorshop' ),
			'SK' => __( 'Slovakia', 'colorshop' ),
			'SI' => __( 'Slovenia', 'colorshop' ),
			'SB' => __( 'Solomon Islands', 'colorshop' ),
			'SO' => __( 'Somalia', 'colorshop' ),
			'ZA' => __( 'South Africa', 'colorshop' ),
			'GS' => __( 'South Georgia/Sandwich Islands', 'colorshop' ),
			'KR' => __( 'South Korea', 'colorshop' ),
			'SS' => __( 'South Sudan', 'colorshop' ),
			'ES' => __( 'Spain', 'colorshop' ),
			'LK' => __( 'Sri Lanka', 'colorshop' ),
			'SD' => __( 'Sudan', 'colorshop' ),
			'SR' => __( 'Suriname', 'colorshop' ),
			'SJ' => __( 'Svalbard and Jan Mayen', 'colorshop' ),
			'SZ' => __( 'Swaziland', 'colorshop' ),
			'SE' => __( 'Sweden', 'colorshop' ),
			'CH' => __( 'Switzerland', 'colorshop' ),
			'SY' => __( 'Syria', 'colorshop' ),
			'TW' => __( 'Taiwan', 'colorshop' ),
			'TJ' => __( 'Tajikistan', 'colorshop' ),
			'TZ' => __( 'Tanzania', 'colorshop' ),
			'TH' => __( 'Thailand', 'colorshop' ),
			'TL' => __( 'Timor-Leste', 'colorshop' ),
			'TG' => __( 'Togo', 'colorshop' ),
			'TK' => __( 'Tokelau', 'colorshop' ),
			'TO' => __( 'Tonga', 'colorshop' ),
			'TT' => __( 'Trinidad and Tobago', 'colorshop' ),
			'TN' => __( 'Tunisia', 'colorshop' ),
			'TR' => __( 'Turkey', 'colorshop' ),
			'TM' => __( 'Turkmenistan', 'colorshop' ),
			'TC' => __( 'Turks and Caicos Islands', 'colorshop' ),
			'TV' => __( 'Tuvalu', 'colorshop' ),
			'UG' => __( 'Uganda', 'colorshop' ),
			'UA' => __( 'Ukraine', 'colorshop' ),
			'AE' => __( 'United Arab Emirates', 'colorshop' ),
			'GB' => __( 'United Kingdom', 'colorshop' ),
			'US' => __( 'United States', 'colorshop' ),
			'UY' => __( 'Uruguay', 'colorshop' ),
			'UZ' => __( 'Uzbekistan', 'colorshop' ),
			'VU' => __( 'Vanuatu', 'colorshop' ),
			'VA' => __( 'Vatican', 'colorshop' ),
			'VE' => __( 'Venezuela', 'colorshop' ),
			'VN' => __( 'Vietnam', 'colorshop' ),
			'WF' => __( 'Wallis and Futuna', 'colorshop' ),
			'EH' => __( 'Western Sahara', 'colorshop' ),
			'WS' => __( 'Western Samoa', 'colorshop' ),
			'YE' => __( 'Yemen', 'colorshop' ),
			'ZM' => __( 'Zambia', 'colorshop' ),
			'ZW' => __( 'Zimbabwe', 'colorshop' )
		));

		// States set to array() are blank i.e. the country has no use for the state field.
		$states = array(
			'AF' => array(),
			'AT' => array(),
			'BE' => array(),
			'BI' => array(),
			'CZ' => array(),
			'DE' => array(),
			'DK' => array(),
			'FI' => array(),
			'FR' => array(),
			'HU' => array(),
			'IS' => array(),
			'IL' => array(),
			'KR' => array(),
			'NL' => array(),
			'NO' => array(),
			'PL' => array(),
			'PT' => array(),
			'SG' => array(),
			'SK' => array(),
			'SI' => array(),
			'LK' => array(),
			'SE' => array(),
			'VN' => array(),
		);

		// Load only the state files the shop owner wants/needs
		$allowed = $this->get_allowed_countries();

		if ( $allowed )
			foreach ( $allowed as $CC => $country )
				if ( ! isset( $states[ $CC ] ) && file_exists( $colorshop->plugin_path() . '/i18n/states/' . $CC . '.php' ) )
					include( $colorshop->plugin_path() . '/i18n/states/' . $CC . '.php' );

		$this->states = apply_filters('colorshop_states', $states );
	}


	/**
	 * Get the base country for the store.
	 *
	 * @access public
	 * @return string
	 */
	public function get_base_country() {
		$default = esc_attr( get_option('colorshop_default_country') );
		if ( ( $pos = strpos( $default, ':' ) ) === false )
			return $default;
		return substr( $default, 0, $pos );
	}


	/**
	 * Get the base state for the state.
	 *
	 * @access public
	 * @return string
	 */
	public function get_base_state() {
		$default = esc_attr( get_option( 'colorshop_default_country' ) );
		if ( ( $pos = strrpos( $default, ':' ) ) === false )
			return '';
		return substr( $default, $pos + 1 );
	}


	/**
	 * Get the allowed countries for the store.
	 *
	 * @access public
	 * @return array
	 */
	public function get_allowed_countries() {

		if ( apply_filters('colorshop_sort_countries', true ) )
			asort( $this->countries );

		if ( get_option('colorshop_allowed_countries') !== 'specific' )
			return $this->countries;

		$allowed_countries = array();

		$allowed_countries_raw = get_option( 'colorshop_specific_allowed_countries' );

		foreach ( $allowed_countries_raw as $country )
			$allowed_countries[ $country ] = $this->countries[ $country ];

		return $allowed_countries;
	}


	/**
	 * get_allowed_country_states function.
	 *
	 * @access public
	 * @return array
	 */
	public function get_allowed_country_states() {

		if ( get_option('colorshop_allowed_countries') !== 'specific' )
			return $this->states;

		$allowed_states = array();

		$allowed_countries_raw = get_option( 'colorshop_specific_allowed_countries' );

		foreach ( $allowed_countries_raw as $country )
			if ( ! empty( $this->states[ $country ] ) )
				$allowed_states[ $country ] = $this->states[ $country ];

		return $allowed_states;
	}


	/**
	 * Gets an array of countries in the EU.
	 *
	 * @access public
	 * @return array
	 */
	public function get_european_union_countries() {
		return array( 'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GB', 'GR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK' );
	}


	/**
	 * Gets the correct string for shipping - ether 'to the' or 'to'
	 *
	 * @access public
	 * @return string
	 */
	public function shipping_to_prefix() {
		global $colorshop;
		$return = '';
		if (in_array($colorshop->customer->get_shipping_country(), array( 'GB', 'US', 'AE', 'CZ', 'DO', 'NL', 'PH', 'USAF' ))) $return = __( 'to the', 'colorshop' );
		else $return = __( 'to', 'colorshop' );
		return apply_filters('colorshop_countries_shipping_to_prefix', $return, $colorshop->customer->get_shipping_country());
	}


	/**
	 * Prefix certain countries with 'the'
	 *
	 * @access public
	 * @return string
	 */
	public function estimated_for_prefix() {
		$return = '';
		if (in_array($this->get_base_country(), array( 'GB', 'US', 'AE', 'CZ', 'DO', 'NL', 'PH', 'USAF' ))) $return = __( 'the', 'colorshop' ) . ' ';
		return apply_filters('colorshop_countries_estimated_for_prefix', $return, $this->get_base_country());
	}


	/**
	 * Correctly name tax in some countries VAT on the frontend
	 *
	 * @access public
	 * @return string
	 */
	public function tax_or_vat() {
		$return = ( in_array($this->get_base_country(), $this->get_european_union_countries()) ) ? __( 'VAT', 'colorshop' ) : __( 'Tax', 'colorshop' );

		return apply_filters( 'colorshop_countries_tax_or_vat', $return );
	}


	/**
	 * Include the Inc Tax label.
	 *
	 * @access public
	 * @return string
	 */
	public function inc_tax_or_vat() {
		$return = ( in_array($this->get_base_country(), $this->get_european_union_countries()) ) ? __( '(incl. VAT)', 'colorshop' ) : __( '(incl. tax)', 'colorshop' );

		return apply_filters( 'colorshop_countries_inc_tax_or_vat', $return );
	}


	/**
	 * Include the Ex Tax label.
	 *
	 * @access public
	 * @return string
	 */
	public function ex_tax_or_vat() {
		$return = ( in_array($this->get_base_country(), $this->get_european_union_countries()) ) ? __( '(ex. VAT)', 'colorshop' ) : __( '(ex. tax)', 'colorshop' );

		return apply_filters( 'colorshop_countries_ex_tax_or_vat', $return );
	}


	/**
	 * Get the states for a country.
	 *
	 * @access public
	 * @param mixed $cc country code
	 * @return array of states
	 */
	public function get_states( $cc ) {
		if (isset( $this->states[$cc] )) return $this->states[$cc];
	}


	/**
	 * Outputs the list of countries and states for use in dropdown boxes.
	 *
	 * @access public
	 * @param string $selected_country (default: '')
	 * @param string $selected_state (default: '')
	 * @param bool $escape (default: false)
	 * @return void
	 */
	public function country_dropdown_options( $selected_country = '', $selected_state = '', $escape = false ) {

		if ( apply_filters('colorshop_sort_countries', true ) )
			asort( $this->countries );

		if ( $this->countries ) foreach ( $this->countries as $key=>$value) :
			if ( $states =  $this->get_states($key) ) :
				echo '<optgroup label="'.$value.'">';
    				foreach ($states as $state_key=>$state_value) :
    					echo '<option value="'.$key.':'.$state_key.'"';

    					if ($selected_country==$key && $selected_state==$state_key) echo ' selected="selected"';

    					echo '>'.$value.' &mdash; '. ($escape ? esc_js($state_value) : $state_value) .'</option>';
    				endforeach;
    			echo '</optgroup>';
			else :
    			echo '<option';
    			if ($selected_country==$key && $selected_state=='*') echo ' selected="selected"';
    			echo ' value="'.$key.'">'. ($escape ? esc_js( $value ) : $value) .'</option>';
			endif;
		endforeach;
	}


	/**
	 * Outputs the list of countries and states for use in multiselect boxes.
	 *
	 * @access public
	 * @param string $selected_countries (default: '')
	 * @param bool $escape (default: false)
	 * @return void
	 */
	public function country_multiselect_options( $selected_countries = '', $escape = false ) {

		$countries = $this->get_allowed_countries();

		foreach ( $countries as $key => $val ) {

			echo '<option value="' . $key . '" ' . selected( isset( $selected_countries[ $key ] ) && in_array( '*', $selected_countries[ $key ] ), true, false ) . '>' . ( $escape ? esc_js( $val ) : $val ) . '</option>';

			if ( $states = $this->get_states( $key ) ) {
				foreach ($states as $state_key => $state_value ) {

	    			echo '<option value="' . $key . ':' . $state_key . '" ' . selected(  isset( $selected_countries[ $key ] ) && in_array( $state_key, $selected_countries[ $key ] ), true, false ) . '>' . ( $escape ? esc_js( $val . ' &gt; ' . $state_value ) : $val . ' &gt; ' . $state_value ) . '</option>';

	    		}
			}

		}
	}


	/**
	 * Get country address formats
	 *
	 * @access public
	 * @return array
	 */
	public function get_address_formats() {

		if (!$this->address_formats) :

			// Common formats
			$postcode_before_city = "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}";

			// Define address formats
			$this->address_formats = apply_filters('colorshop_localisation_address_formats', array(
				'default' => "{name}\n{company}\n{address_1}\n{address_2}\n{city}\n{state}\n{postcode}\n{country}",
				'AU' => "{name}\n{company}\n{address_1}\n{address_2}\n{city} {state} {postcode}\n{country}",
				'AT' => $postcode_before_city,
				'BE' => $postcode_before_city,
				'CH' => $postcode_before_city,
				'CN' => "{country} {postcode}\n{state}, {city}, {address_2}, {address_1}\n{company}\n{name}",
				'CZ' => $postcode_before_city,
				'DE' => $postcode_before_city,
				'FI' => $postcode_before_city,
				'DK' => $postcode_before_city,
				'FR' => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city_upper}\n{country}",
				'HK' => "{company}\n{first_name} {last_name_upper}\n{address_1}\n{address_2}\n{city_upper}\n{state_upper}\n{country}",
				'HU' => "{name}\n{company}\n{city}\n{address_1}\n{address_2}\n{postcode}\n{country}",
				'IS' => $postcode_before_city,
				'IS' => $postcode_before_city,
				'LI' => $postcode_before_city,
				'NL' => $postcode_before_city,
				'NZ' => "{name}\n{company}\n{address_1}\n{address_2}\n{city} {postcode}\n{country}",
				'NO' => $postcode_before_city,
				'PL' => $postcode_before_city,
				'SK' => $postcode_before_city,
				'SI' => $postcode_before_city,
				'ES' => "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city}\n{state}\n{country}",
				'SE' => $postcode_before_city,
				'TR' => "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city} {state}\n{country}",
				'US' => "{name}\n{company}\n{address_1}\n{address_2}\n{city}, {state} {postcode}\n{country}",
				'VN' => "{name}\n{company}\n{address_1}\n{city}\n{country}",
			));
		endif;

		return $this->address_formats;
	}


	/**
	 * Get country address format
	 *
	 * @access public
	 * @param array $args (default: array())
	 * @return string address
	 */
	public function get_formatted_address( $args = array() ) {

		$args = array_map( 'trim', $args );

		extract( $args );

		// Get all formats
		$formats 		= $this->get_address_formats();

		// Get format for the address' country
		$format			= ( $country && isset( $formats[ $country ] ) ) ? $formats[ $country ] : $formats['default'];

		// Handle full country name
		$full_country 	= ( isset( $this->countries[ $country ] ) ) ? $this->countries[ $country ] : $country;

		// Country is not needed if the same as base
		if ( $country == $this->get_base_country() )
			$format = str_replace( '{country}', '', $format );

		// Handle full state name
		$full_state		= ( $country && $state && isset( $this->states[ $country ][ $state ] ) ) ? $this->states[ $country ][ $state ] : $state;

		// Substitute address parts into the string
		$replace = apply_filters( 'colorshop_formatted_address_replacements', array(
			'{first_name}'       => $first_name,
			'{last_name}'        => $last_name,
			'{name}'             => $first_name . ' ' . $last_name,
			'{company}'          => $company,
			'{address_1}'        => $address_1,
			'{address_2}'        => $address_2,
			'{city}'             => $city,
			'{state}'            => $full_state,
			'{postcode}'         => $postcode,
			'{country}'          => $full_country,
			'{first_name_upper}' => strtoupper( $first_name ),
			'{last_name_upper}'  => strtoupper( $last_name ),
			'{name_upper}'       => strtoupper( $first_name . ' ' . $last_name ),
			'{company_upper}'    => strtoupper( $company ),
			'{address_1_upper}'  => strtoupper( $address_1 ),
			'{address_2_upper}'  => strtoupper( $address_2 ),
			'{city_upper}'       => strtoupper( $city ),
			'{state_upper}'      => strtoupper( $full_state ),
			'{postcode_upper}'   => strtoupper( $postcode ),
			'{country_upper}'    => strtoupper( $full_country ),
		) ) ;

		$replace = array_map( 'esc_html', $replace );

		$formatted_address = str_replace( array_keys( $replace ), $replace, $format );

		// Clean up white space
		$formatted_address = preg_replace( '/  +/', ' ', trim( $formatted_address ) );
		$formatted_address = preg_replace( '/\n\n+/', "\n", $formatted_address );

		// Add html breaks
		$formatted_address = nl2br( $formatted_address );

		// We're done!
		return $formatted_address;
	}


	/**
	 * Returns the fields we show by default. This can be filtered later on.
	 *
	 * @access public
	 * @return void
	 */
	public function get_default_address_fields() {
		$fields = array(
			'country' 	=> array(
				'type'			=> 'country',
				'label' 		=> __( 'Country', 'colorshop' ),
				'required' 		=> true,
				'class' 		=> array( 'form-row-wide', 'address-field', 'update_totals_on_change' ),
				),
			'first_name' => array(
				'label' 		=> __( 'First Name', 'colorshop' ),
				'required' 		=> true,
				'class'			=> array( 'form-row-first' ),
				),
			'last_name' => array(
				'label' 		=> __( 'Last Name', 'colorshop' ),
				'required' 		=> true,
				'class' 		=> array( 'form-row-last' ),
				'clear'			=> true
				),
			'company' 	=> array(
				'label' 		=> __( 'Company Name', 'colorshop' ),
				'class' 		=> array( 'form-row-wide' ),
				),
			'address_1' 	=> array(
				'label' 		=> __( 'Address', 'colorshop' ),
				'placeholder' 	=> _x( 'Street address', 'placeholder', 'colorshop' ),
				'required' 		=> true,
				'class' 		=> array( 'form-row-wide', 'address-field' ),
				),
			'address_2' => array(
				'placeholder' 	=> _x( 'Apartment, suite, unit etc. (optional)', 'placeholder', 'colorshop' ),
				'class' 		=> array( 'form-row-wide', 'address-field' ),
				'required' 	    => false
				),
			'city' 		=> array(
				'label' 		=> __( 'Town / City', 'colorshop' ),
				'placeholder'	=> __( 'Town / City', 'colorshop' ),
				'required' 		=> true,
				'class' 		=> array( 'form-row-wide', 'address-field' ),
				),
			'state' 	=> array(
				'type'			=> 'state',
				'label' 		=> __( 'State / County', 'colorshop' ),
				'placeholder' 	=> __( 'State / County', 'colorshop' ),
				'required' 		=> true,
				'class' 		=> array( 'form-row-first', 'address-field' )
				),
			'postcode' 	=> array(
				'label' 		=> __( 'Postcode / Zip', 'colorshop' ),
				'placeholder' 	=> __( 'Postcode / Zip', 'colorshop' ),
				'required' 		=> true,
				'class'			=> array( 'form-row-last', 'address-field' ),
				'clear'			=> true
				),
		);

		return apply_filters( 'colorshop_default_address_fields', $fields );
	}

	/**
	 * Get country locale settings
	 *
	 * @access public
	 * @return array
	 */
	public function get_country_locale() {
		if ( ! $this->locale ) {

			// Locale information used by the checkout
			$this->locale = apply_filters('colorshop_get_country_locale', array(
				'AF' => array(
					'state' => array(
						'required' => false,
					),
				),
				'AT' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'BE' => array(
					'postcode_before_city' => true,
					'state' => array(
						'required' => false,
						'label'    => __( 'Province', 'colorshop' ),
					),
				),
				'BI' => array(
					'state' => array(
						'required' => false,
					),
				),
				'CA' => array(
					'state'	=> array(
						'label'			=> __( 'Province', 'colorshop' ),
					)
				),
				'CH' => array(
                    'postcode_before_city' => true,
                    'state' => array(
                        'label'         => __( 'Canton', 'colorshop' ),
                        'required'      => false
                    )
                ),
				'CL' => array(
					'city'		=> array(
						'required' 	=> false,
					),
					'state'		=> array(
						'label'			=> __( 'Municipality', 'colorshop' ),
					)
				),
				'CN' => array(
					'state'	=> array(
						'label'			=> __( 'Province', 'colorshop' ),
					)
				),
				'CO' => array(
					'postcode' => array(
						'required' 	=> false
					)
				),
				'CZ' => array(
					'state'		=> array(
						'required' => false
					)
				),
				'DE' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'DK' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'FI' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'FR' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'HK' => array(
					'postcode'	=> array(
						'required' => false
					),
					'city'	=> array(
						'label'				=> __( 'Town / District', 'colorshop' ),
					),
					'state'		=> array(
						'label' 		=> __( 'Region', 'colorshop' ),
					)
				),
				'HU' => array(
					'state'		=> array(
						'required' => false
					)
				),
				'ID' => array(
	                'state' => array(
	                    'label'         => __( 'Province', 'colorshop' ),
	                )
            	),
				'IS' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'IL' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'KR' => array(
					'state'		=> array(
						'required' => false
					)
				),
				'NL' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false,
						'label'    => __( 'Province', 'colorshop' ),
					)
				),
				'NZ' => array(
					'state'		=> array(
						'required' => false
					)
				),
				'NO' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'PL' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'PT' => array(
					'state'		=> array(
						'required' => false
					)
				),
				'RO' => array(
					'state'		=> array(
						'required' => false
					)
				),
				'SG' => array(
					'state'		=> array(
						'required' => false
					)
				),
				'SK' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'SI' => array(
					'postcode_before_city' => true,
					'state'		=> array(
						'required' => false
					)
				),
				'ES' => array(
					'postcode_before_city' => true,
					'state'	=> array(
						'label'			=> __( 'Province', 'colorshop' ),
					)
				),
				'LI' => array(
                    'postcode_before_city' => true,
                    'state' => array(
                        'label'         => __( 'Municipality', 'colorshop' ),
                        'required'      => false
                    )
                ),
				'LK' => array(
					'state'	=> array(
						'required' => false
					)
				),
				'SE' => array(
					'postcode_before_city' => true,
					'state'	=> array(
						'required' => false
					)
				),
				'TR' => array(
					'postcode_before_city' => true,
					'state'	=> array(
						'label'			=> __( 'Province', 'colorshop' ),
					)
				),
				'US' => array(
					'postcode'	=> array(
						'label' 		=> __( 'Zip', 'colorshop' ),
					),
					'state'		=> array(
						'label' 		=> __( 'State', 'colorshop' ),
					)
				),
				'GB' => array(
					'postcode'	=> array(
						'label' 		=> __( 'Postcode', 'colorshop' ),
					),
					'state'		=> array(
						'label' 		=> __( 'County', 'colorshop' ),
						'required' 		=> false
					)
				),
				'VN' => array(
					'state'		=> array(
						'required' => false
					),
					'postcode' => array(
						'required' 	=> false,
						'hidden'	=> true
					),
					'address_2' => array(
						'required' 	=> false,
						'hidden'	=> true
					)
				),
				'WS' => array(
					'postcode' => array(
						'required' 	=> false,
						'hidden'	=> true
					),
				),
				'ZA' => array(
					'state'	=> array(
						'label'			=> __( 'Province', 'colorshop' ),
					)
				),
				'ZW' => array(
					'postcode' => array(
						'required' 	=> false,
						'hidden'	=> true
					),
				),
			));

			$this->locale = array_intersect_key( $this->locale, $this->get_allowed_countries() );

			// Default Locale Can be filters to override fields in get_address_fields().
			// Countries with no specific locale will use default.
			$this->locale['default'] = apply_filters('colorshop_get_country_locale_default', $this->get_default_address_fields() );

			// Filter default AND shop base locales to allow overides via a single function. These will be used when changing countries on the checkout
			if ( ! isset( $this->locale[ $this->get_base_country() ] ) )
				$this->locale[ $this->get_base_country() ] = $this->locale['default'];

			$this->locale['default'] 					= apply_filters( 'colorshop_get_country_locale_base', $this->locale['default'] );
			$this->locale[ $this->get_base_country() ] 	= apply_filters( 'colorshop_get_country_locale_base', $this->locale[ $this->get_base_country() ] );
		}

		return $this->locale;
	}

	/**
	 * Apply locale and get address fields
	 *
	 * @access public
	 * @param mixed $country
	 * @param string $type (default: 'billing_')
	 * @return void
	 */
	public function get_address_fields( $country, $type = 'billing_' ) {
		$fields     = $this->get_default_address_fields();
		$locale		= $this->get_country_locale();

		if ( isset( $locale[ $country ] ) ) {

			$fields = colorshop_array_overlay( $fields, $locale[ $country ] );

			// If default country has postcode_before_city switch the fields round.
			// This is only done at this point, not if country changes on checkout.
			if ( isset( $locale[ $country ]['postcode_before_city'] ) ) {
				if ( isset( $fields['postcode'] ) ) {
					$fields['postcode']['class'] = array( 'form-row-wide', 'address-field' );

					$switch_fields = array();

					foreach ( $fields as $key => $value ) {
						if ( $key == 'city' ) {
							// Place postcode before city
							$switch_fields['postcode'] = '';
						}
						$switch_fields[$key] = $value;
					}

					$fields = $switch_fields;
				}
			}
		}

		// Prepend field keys
		$address_fields = array();

		foreach ( $fields as $key => $value ) {
			$address_fields[$type . $key] = $value;
		}

		// Billing/Shipping Specific
		if ( $type == 'billing_' ) {

			$address_fields['billing_email'] = array(
				'label' 		=> __( 'Email Address', 'colorshop' ),
				'required' 		=> true,
				'class' 		=> array( 'form-row-first' ),
				'validate'		=> array( 'email' ),
			);
			$address_fields['billing_phone'] = array(
				'label' 		=> __( 'Phone', 'colorshop' ),
				'required' 		=> true,
				'class' 		=> array( 'form-row-last' ),
				'clear'			=> true
			);

			$address_fields = apply_filters( 'colorshop_billing_fields', $address_fields, $country );
		} else {
			$address_fields = apply_filters( 'colorshop_shipping_fields', $address_fields, $country );
		}

		// Return
		return $address_fields;
	}
}
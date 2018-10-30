<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Parent theme: Bootstrapbase by Bas navbar-brands
 * Built on: Essential by Julian Ridden
 *
 * @package   theme_itrackglobal
 * @copyright 2014 redPIthemes
 *
 */
use core_completion\progress;
require_once($CFG->libdir . '/completionlib.php');

function theme_itrackglobal_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        $theme = theme_config::load('itrackglobal');		
		if ($filearea === 'logo') {
            return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
        } else if ($filearea === 'pagebackground') {
            return $theme->setting_file_serve('pagebackground', $args, $forcedownload, $options);
		} else if ($filearea === 'slide1image') {
            return $theme->setting_file_serve('slide1image', $args, $forcedownload, $options);
        } else if ($filearea === 'slide2image') {
            return $theme->setting_file_serve('slide2image', $args, $forcedownload, $options);
        } else if ($filearea === 'slide3image') {
            return $theme->setting_file_serve('slide3image', $args, $forcedownload, $options);
        } else if ($filearea === 'slide4image') {
            return $theme->setting_file_serve('slide4image', $args, $forcedownload, $options);
        } else if ($filearea === 'slide5image') {
            return $theme->setting_file_serve('slide5image', $args, $forcedownload, $options);
        } else if ((substr($filearea, 0, 15) === 'carousel_image_')) {
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);		
		} else {
            send_file_not_found();
        }
    } else {
        send_file_not_found();
    }
}

function itrackglobal_set_pagewidth1($css, $pagewidth) {
    $tag = '[[setting:pagewidth]]';
    $replacement = $pagewidth;
    if (is_null($replacement)) {
        $replacement = '1600';
    }
    if ( ($replacement == "90") || ($replacement == "100") ) {
		$css = str_replace($tag, $replacement.'%', $css);
	} else {
		$css = str_replace($tag, $replacement.'px', $css);
	}
    return $css;
}

function itrackglobal_set_pagewidth2($css, $pagewidth) {
    $tag = '[[setting:pagewidth_wide]]';
    if ($pagewidth == "100") {
        $replacement = 'body {background:none repeat scroll 0 0 #fff;padding-top:0;} @media(max-width:767px){body {padding-left: 0; padding-right: 0;} #page {padding: 10px 0;}} #wrapper {max-width:100%;width:100%;} #page-header {margin:0 auto;max-width:90%;} .container-fluid {padding: 0; max-width:100%} .navbar {background: none repeat scroll 0 0 [[setting:menufirstlevelcolor]];padding: 0;} .navbar-inner {margin: 0 auto; max-width: 90%;} .navbar-navbar-navbar-brand {margin-left:0;} .navbar #search {margin-right:0;} .slidershadow.frontpage-shadow {display:none;} .camera_wrap {margin-top: -10px;} #page-content.row {margin: 0 auto; max-width: 90%;} #page-footer .row {margin: 0 auto; max-width: 90%;} .spotlight-full {margin-left: -5.8% !important; margin-right: -5.8% !important;} .socials-header .social_icons.pull-right {padding-right:10%;} .socials-header .social_contact {padding-left:10%;}';
    }
	$css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_fontsrc($css) {
    $tag = '[[setting:fontsrc]]';
	$themewww = $CFG->wwwroot."/theme";
    $css = str_replace($tag, $themewww.'/itrackglobal/fonts/', $css);
    return $css;
}

function itrackglobal_set_customcss($css, $customcss) {
    $tag = '[[setting:customcss]]';
    $replacement = $customcss;
    if (is_null($replacement)) {
        $replacement = '';
    }

    $css = str_replace($tag, $replacement, $css);

    return $css;
}

function theme_itrackglobal_process_css($css, $theme) {

    if (!empty($theme->settings->pagewidth)) {
       $pagewidth = $theme->settings->pagewidth;
    } else {
       $pagewidth = null;
    }
    $css = itrackglobal_set_pagewidth1($css,$pagewidth);
	$css = itrackglobal_set_pagewidth2($css,$pagewidth);  
    // Set the Fonts.
    if ($theme->settings->font_body ==1) {
        $bodyfont = 'open_sansregular';
        $bodysize = '13px';
        $bodyweight = '400';
    } else if ($theme->settings->font_body ==2) {
        $bodyfont = 'Arimo';
        $bodysize = '13px';
        $bodyweight = '400';
    } else if ($theme->settings->font_body ==3) {
        $bodyfont = 'Arvo';
        $bodysize = '13px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==4) {
        $bodyfont = 'Bree Serif';
        $bodysize = '13px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==5) {
        $bodyfont = 'Cabin';
        $bodysize = '13px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==6) {
        $bodyfont = 'Cantata One';
        $bodysize = '13px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==7) {
        $bodyfont = 'Crimson Text';
        $bodysize = '14px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==8) {
        $bodyfont = 'Droid Sans';
        $bodysize = '13px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==9) {
        $bodyfont = 'Droid Serif';
        $bodysize = '13px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==10) {
        $bodyfont = 'Gudea';
        $bodysize = '13px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==11) {
        $bodyfont = 'Imprima';
        $bodysize = '13px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==12) {
        $bodyfont = 'Lekton';
        $bodysize = '13px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==13) {
        $bodyfont = 'Nixie One';
        $bodysize = '13px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==14) {
        $bodyfont = 'Nobile';
        $bodysize = '12px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==15) {
        $bodyfont = 'Playfair Display';
        $bodysize = '13px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==16) {
        $bodyfont = 'Pontano Sans';
        $bodysize = '13px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==17) {
        $bodyfont = 'PT Sans';
        $bodysize = '14px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==18) {
        $bodyfont = 'Raleway';
        $bodysize = '13px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==19) {
        $bodyfont = 'Ubuntu';
        $bodysize = '13px';
        $bodyweight = '400';
	} else if ($theme->settings->font_body ==20) {
        $bodyfont = 'Vollkorn';
        $bodysize = '14px';
        $bodyweight = '400';}	
		
	if ($theme->settings->font_heading ==1) {
        $headingfont = 'open_sansbold';
    } else if ($theme->settings->font_heading ==2) {
        $headingfont = 'Abril Fatface';
    } else if ($theme->settings->font_heading ==3) {
        $headingfont = 'Arimo';
    } else if ($theme->settings->font_heading ==4) {
        $headingfont = 'Arvo';
    } else if ($theme->settings->font_heading ==5) {
        $headingfont = 'Bevan';
    } else if ($theme->settings->font_heading ==6) {
        $headingfont = 'Bree Serif';
    } else if ($theme->settings->font_heading ==7) {
        $headingfont = 'Cabin';
    } else if ($theme->settings->font_heading ==8) {
        $headingfont = 'Cantata One';
    } else if ($theme->settings->font_heading ==9) {
        $headingfont = 'Crimson Text';
    } else if ($theme->settings->font_heading ==10) {
        $headingfont = 'Droid Sans';
    } else if ($theme->settings->font_heading ==11) {
        $headingfont = 'Droid Serif';
    } else if ($theme->settings->font_heading ==12) {
        $headingfont = 'Gudea';
    } else if ($theme->settings->font_heading ==13) {
        $headingfont = 'Imprima';
    } else if ($theme->settings->font_heading ==14) {
        $headingfont = 'Josefin Sans';
    } else if ($theme->settings->font_heading ==15) {
        $headingfont = 'Lekton';
    } else if ($theme->settings->font_heading ==16) {
        $headingfont = 'Lobster';
    } else if ($theme->settings->font_heading ==17) {
        $headingfont = 'Nixie One';
    } else if ($theme->settings->font_heading ==18) {
        $headingfont = 'Nobile';
    } else if ($theme->settings->font_heading ==19) {
        $headingfont = 'Pacifico';
    } else if ($theme->settings->font_heading ==20) {
        $headingfont = 'Playfair Display';
    } else if ($theme->settings->font_heading ==21) {
        $headingfont = 'Pontano Sans';
    } else if ($theme->settings->font_heading ==22) {
        $headingfont = 'PT Sans';
    } else if ($theme->settings->font_heading ==23) {
        $headingfont = 'Raleway';
    } else if ($theme->settings->font_heading ==24) {
        $headingfont = 'Sansita One';
    } else if ($theme->settings->font_heading ==25) {
        $headingfont = 'Ubuntu';
    } else if ($theme->settings->font_heading ==26) {
        $headingfont = 'Vollkorn';}
    
    $css = theme_itrackglobal_set_headingfont($css, $headingfont);
    $css = theme_itrackglobal_set_bodyfont($css, $bodyfont);
  
    if (!empty($theme->settings->customcss)) {
        $customcss = $theme->settings->customcss;
    } else {
        $customcss = null;
    }
    $css = itrackglobal_set_customcss($css, $customcss);
	
    if (!empty($theme->settings->maincolor)) {
        $maincolor = $theme->settings->maincolor;
    } else {
        $maincolor = null;
    }
    $css = theme_itrackglobal_set_maincolor($css, $maincolor);

    if (!empty($theme->settings->mainhovercolor)) {
        $mainhovercolor = $theme->settings->mainhovercolor;
    } else {
        $mainhovercolor = null;
    }
    $css = theme_itrackglobal_set_mainhovercolor($css, $mainhovercolor);
	
	if (!empty($theme->settings->linkcolor)) {
        $linkcolor = $theme->settings->linkcolor;
    } else {
        $linkcolor = null;
    }
    $css = theme_itrackglobal_set_linkcolor($css, $linkcolor);

    if (!empty($theme->settings->def_buttoncolor)) {
        $def_buttoncolor = $theme->settings->def_buttoncolor;
    } else {
        $def_buttoncolor = null;
    }
    $css = theme_itrackglobal_set_def_buttoncolor($css, $def_buttoncolor);

    if (!empty($theme->settings->def_buttonhovercolor)) {
        $def_buttonhovercolor = $theme->settings->def_buttonhovercolor;
    } else {
        $def_buttonhovercolor = null;
    }
    $css = theme_itrackglobal_set_def_buttonhovercolor($css, $def_buttonhovercolor);

    if (!empty($theme->settings->menufirstlevelcolor)) {
        $menufirstlevelcolor = $theme->settings->menufirstlevelcolor;
    } else {
        $menufirstlevelcolor = null;
    }
    $css = theme_itrackglobal_set_menufirstlevelcolor($css, $menufirstlevelcolor);

    if (!empty($theme->settings->menufirstlevel_linkcolor)) {
        $menufirstlevel_linkcolor = $theme->settings->menufirstlevel_linkcolor;
    } else {
        $menufirstlevel_linkcolor = null;
    }
    $css = theme_itrackglobal_set_menufirstlevel_linkcolor($css, $menufirstlevel_linkcolor);

    if (!empty($theme->settings->menusecondlevelcolor)) {
        $menusecondlevelcolor = $theme->settings->menusecondlevelcolor;
    } else {
        $menusecondlevelcolor = null;
    }
    $css = theme_itrackglobal_set_menusecondlevelcolor($css, $menusecondlevelcolor);

    if (!empty($theme->settings->menusecondlevel_linkcolor)) {
        $menusecondlevel_linkcolor = $theme->settings->menusecondlevel_linkcolor;
    } else {
        $menusecondlevel_linkcolor = null;
    }
    $css = theme_itrackglobal_set_menusecondlevel_linkcolor($css, $menusecondlevel_linkcolor);

    if (!empty($theme->settings->footercolor)) {
        $footercolor = $theme->settings->footercolor;
    } else {
        $footercolor = null;
    }
    $css = theme_itrackglobal_set_footercolor($css, $footercolor);

    if (!empty($theme->settings->footerheadingcolor)) {
        $footerheadingcolor = $theme->settings->footerheadingcolor;
    } else {
        $footerheadingcolor = null;
    }
    $css = theme_itrackglobal_set_footerheadingcolor($css, $footerheadingcolor);

    if (!empty($theme->settings->footertextcolor)) {
        $footertextcolor = $theme->settings->footertextcolor;
    } else {
        $footertextcolor = null;
    }
    $css = theme_itrackglobal_set_footertextcolor($css, $footertextcolor);
	
    if (!empty($theme->settings->copyrightcolor)) {
        $copyrightcolor = $theme->settings->copyrightcolor;
    } else {
        $copyrightcolor = null;
    }
    $css = theme_itrackglobal_set_copyrightcolor($css, $copyrightcolor);

    if (!empty($theme->settings->copyright_textcolor)) {
        $copyright_textcolor = $theme->settings->copyright_textcolor;
    } else {
        $copyright_textcolor = null;
    }
	$css = theme_itrackglobal_set_copyright_textcolor($css, $copyright_textcolor);
	
	if (!empty($theme->settings->socials_color)) {
        $socials_color = $theme->settings->socials_color;
    } else {
        $socials_color = null;
    }
    $css = theme_itrackglobal_set_socials_color($css, $socials_color);

    $setting = 'list_bg';
	if (is_null($theme->setting_file_url('pagebackground', 'pagebackground'))) {
    	global $OUTPUT;
		if ($theme->settings->list_bg==1)  {
        	$pagebackground = $OUTPUT->pix_url('page_bg/page_bg_02', 'theme');
			$repeat = 'no-repeat fixed 0 0';
			$size = 'cover';}
		else if ($theme->settings->list_bg==2)  {
        	$pagebackground = $OUTPUT->pix_url('page_bg/page_bg_blur01', 'theme');
			$repeat = 'no-repeat fixed 0 0';
			$size = 'cover';}
		else if ($theme->settings->list_bg==3)  {
        	$pagebackground = $OUTPUT->pix_url('page_bg/page_bg_blur02', 'theme');
			$repeat = 'no-repeat fixed 0 0';
			$size = 'cover';}
		else if ($theme->settings->list_bg==4)  {
        	$pagebackground = $OUTPUT->pix_url('page_bg/page_bg_blur03', 'theme');
			$repeat = 'no-repeat fixed 0 0';
			$size = 'cover';}
		else if ($theme->settings->list_bg==5)  {
        	$pagebackground = $OUTPUT->pix_url('page_bg/cream_pixels', 'theme');
			$repeat = 'repeat fixed 0 0';
			$size = 'auto';}
		else if ($theme->settings->list_bg==6)  {
        	$pagebackground = $OUTPUT->pix_url('page_bg/mochaGrunge', 'theme');
			$repeat = 'repeat fixed 0 0';
			$size = 'auto';}
		else if ($theme->settings->list_bg==7)  {
        	$pagebackground = $OUTPUT->pix_url('page_bg/skulls', 'theme');
			$repeat = 'repeat fixed 0 0';
			$size = 'auto';}
		else if ($theme->settings->list_bg==8)  {
        	$pagebackground = $OUTPUT->pix_url('page_bg/sos', 'theme');
			$repeat = 'repeat fixed 0 0';
			$size = 'auto';}
		else if ($theme->settings->list_bg==9)  {
        	$pagebackground = $OUTPUT->pix_url('page_bg/squairy_light', 'theme');
			$repeat = 'repeat fixed 0 0';
			$size = 'auto';}
		else if ($theme->settings->list_bg==10)  {
        	$pagebackground = $OUTPUT->pix_url('page_bg/subtle_white_feathers', 'theme');
			$repeat = 'repeat fixed 0 0';
			$size = 'auto';}
		else if ($theme->settings->list_bg==11)  {
        	$pagebackground = $OUTPUT->pix_url('page_bg/tweed', 'theme');
			$repeat = 'repeat fixed 0 0';
			$size = 'auto';}
		else if ($theme->settings->list_bg==12)  {
        	$pagebackground = $OUTPUT->pix_url('page_bg/wet_snow', 'theme');
			$repeat = 'repeat fixed 0 0';
			$size = 'auto';}
		else {
			$pagebackground = $OUTPUT->pix_url('page_bg/page_bg_01', 'theme');
			$repeat = 'no-repeat fixed 0 0';
			$size = 'cover';}
		$css = theme_itrackglobal_set_pagebackground($css, $pagebackground, $setting);
		$css = theme_itrackglobal_set_background_repeat($css, $repeat, $size);
    }

    $setting = 'pagebackground';
    $pagebackground = $theme->setting_file_url($setting, $setting);
    $css = theme_itrackglobal_set_pagebackground($css, $pagebackground, $setting);
	
	$repeat = 'no-repeat fixed 0 0';
	$size = 'cover';
    if ($theme->settings->page_bg_repeat==1)  {
        $replacement = 'repeat fixed 0 0';
		$size = 'auto';
    }
    $css = theme_itrackglobal_set_background_repeat($css, $repeat, $size);
	
	$css = theme_itrackglobal_set_fontsrc($css);
	
    return $css;
}

function theme_itrackglobal_set_headingfont($css, $headingfont) {
    $tag = '[[setting:headingfont]]';
    $replacement = $headingfont;
    if (is_null($replacement)) {
        $replacement = 'open_sansbold';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_bodyfont($css, $bodyfont) {
    $tag = '[[setting:bodyfont]]';
    $replacement = $bodyfont;
    if (is_null($replacement)) {
        $replacement = 'open_sansregular';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_maincolor($css, $themecolor) {
    $tag = '[[setting:maincolor]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#e2a500';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_mainhovercolor($css, $themecolor) {
    $tag = '[[setting:mainhovercolor]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#c48f00';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_linkcolor($css, $themecolor) {
    $tag = '[[setting:linkcolor]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#966b00';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_def_buttoncolor($css, $themecolor) {
    $tag = '[[setting:def_buttoncolor]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#8ec63f';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_def_buttonhovercolor($css, $themecolor) {
    $tag = '[[setting:def_buttonhovercolor]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#77ae29';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_menufirstlevelcolor($css, $themecolor) {
    $tag = '[[setting:menufirstlevelcolor]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#323A45';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_menufirstlevel_linkcolor($css, $themecolor) {
    $tag = '[[setting:menufirstlevel_linkcolor]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#ffffff';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_menusecondlevelcolor($css, $themecolor) {
    $tag = '[[setting:menusecondlevelcolor]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#f4f4f4';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_menusecondlevel_linkcolor($css, $themecolor) {
    $tag = '[[setting:menusecondlevel_linkcolor]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#444444';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_footercolor($css, $themecolor) {
    $tag = '[[setting:footercolor]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#323A45';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_footerheadingcolor($css, $themecolor) {
    $tag = '[[setting:footerheadingcolor]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#f9f9f9';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_footertextcolor($css, $themecolor) {
    $tag = '[[setting:footertextcolor]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#bdc3c7';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_copyrightcolor($css, $themecolor) {
    $tag = '[[setting:copyrightcolor]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#292F38';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_copyright_textcolor($css, $themecolor) {
    $tag = '[[setting:copyright_textcolor]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#bdc3c7';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_socials_color($css, $themecolor) {
    $tag = '[[setting:socials_color]]';
    $replacement = $themecolor;
    if (is_null($replacement)) {
        $replacement = '#a9a9a9';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_pagebackground($css, $pagebackground, $setting) {
    global $OUTPUT;
    $tag = '[[setting:pagebackground]]';
    $replacement = $pagebackground;
    if (is_null($replacement)) {
        $replacement = $OUTPUT->pix_url('page_bg/page_bg_01', 'theme');
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function theme_itrackglobal_set_background_repeat($css, $repeat, $size) {
    $tag = '[[setting:background-repeat]]';
    $css = str_replace($tag, $repeat, $css);
	$tag = '[[setting:background-size]]';
    $css = str_replace($tag, $size, $css);
    return $css;
}

/*function theme_itrackglobal_page_init(moodle_page $page) {
    $page->requires->jquery();
	$page->requires->jquery_plugin('jquery.easing.1.3', 'theme_itrackglobal'); 
	$page->requires->jquery_plugin('camera_slider', 'theme_itrackglobal');
    $page->requires->jquery_plugin('jquery.bxslider', 'theme_itrackglobal'); 
    $page->requires->jquery_plugin('jquery.slimscroll', 'theme_itrackglobal'); 
}*/
function get_my_courses_progress(){
    global $CFG,$DB,$USER;
    if (empty($CFG->navsortmycoursessort)) {
        $sort = 'visible DESC, sortorder ASC';
    } else {
        $sort = 'visible DESC, '.$CFG->navsortmycoursessort.' ASC';
    }

    $courses = enrol_get_my_courses('*', $sort);
    $coursesprogress = [];
    //print_object($courses);
    foreach ($courses as $course) {

        $completion = new \completion_info($course);

        // First, let's make sure completion is enabled.
        if (!$completion->is_enabled()) {
            continue;
        }

        $percentage = progress::get_course_progress_percentage($course);
        //print_object($percentage);
        if (!is_null($percentage)) {
            $percentage = floor($percentage);
        }

        $coursesprogress[$course->id]['completed'] = $completion->is_course_complete($USER->id);
        $coursesprogress[$course->id]['progress'] = $percentage;
       
    }
    return $coursesprogress;
}

function left_menubar(){
    global $DB,$CFG,$OUTPUT,$PAGE,$USER;
    $sidebar_content = '<nav class="sidebar-nav">
                    <ul id="sidebarnav">';
    $sidebar_content .= '<li><a class="waves-effect waves-dark" href="'.$CFG->wwwroot.'/my" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard </span></a></li>';
    if(is_siteadmin()){ //superadmin
        $sidebar_content .= superadmin_menu();
    }elseif(user_has_role_assignment($USER->id, 9, SYSCONTEXTID)) { //account manager
        $sidebar_content .= account_manager_menu();
    }elseif(user_has_role_assignment($USER->id, 10, SYSCONTEXTID)) { //partner admin
        $sidebar_content .= partner_admin_menu();
    }elseif(user_has_role_assignment($USER->id, 11, SYSCONTEXTID)) { //training partner
        $sidebar_content .= training_partner_menu();
    }elseif(user_has_role_assignment($USER->id, 12, SYSCONTEXTID)) { //professor 
        $sidebar_content .= professor_menu();
    }else{ //student
        $sidebar_content .= student_menu();
    }
    $sidebar_content .= '</ul>
                </nav>';
    /*$sidebar_content .= "<script>
                        var acc = document.getElementsByClassName('accordion');
                        var i;

                        for (i = 0; i < acc.length; i++) {
                          acc[i].addEventListener('click', function() {
                            this.classList.toggle('active');
                            var panel = this.nextElementSibling;
                            if (panel.style.maxHeight){
                              panel.style.maxHeight = null;
                            } else {
                              panel.style.maxHeight = panel.scrollHeight + 'px';
                            } 
                          });
                        }
                        </script>";*/
    return $sidebar_content;
}
function superadmin_menu(){
        global $DB,$CFG,$OUTPUT,$PAGE,$USER;
        $sidebar_admin = '';
        $sidebar_admin .= '<li>
                                <a class="" href="'.$CFG->wwwroot.'/user/editadvanced.php?id=-1">
                                    <i class="fa fa-user"></i>
                                    <span class="text">Create Account Manager</span>
                                </a>
                            </li>
                            <li>
                                <a class="" href="#">
                                    <i class="fa fa-book"></i>
                                    <span class="text">Order Lists</span>
                                </a>
                            </li>
                            <li>
                                <a class="" href="#">
                                    <i class="fa fa-list-alt"></i>
                                    <span class="text">eLabs Promotion Rule</span>
                                </a>
                            </li>';
        $sidebar_admin .='<button class="accordion"><i class="fa fa-user"></i><span class="text">Common Functionality</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  iTrack eco value pack</a></li>
                                    <li><a href="#">&#128902;  News</a></li>
                                    <li><a href="#">&#128902;  Assign Premium Pack</a></li>
                                    <li><a href="#">&#128902;  Announcements</a></li>
                                </ul>
                            </div>

                            <button class="accordion"><i class="fa fa-book"></i><span class="text">Reports</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Consolidated</a></li>
                                    <li><a href="#">&#128902;  Register and Package Reports</a></li>
                                    <li><a href="#">&#128902;  Webinar Subscriber</a></li>
                                    <li><a href="#">&#128902;  Special Program Reports</a></li>
                                    <li><a href="#">&#128902;  Login Reports</a></li>
                                </ul>
                            </div>';
        return $sidebar_admin;    
    }

    function account_manager_menu(){
        global $DB,$CFG,$OUTPUT,$PAGE,$USER;
        $acc = $DB->get_records('partners',array('createdby'=>$USER->id));

        $sidebar_account = '';
        $sidebar_account .='<li> 
                                <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-account-settings-variant"></i><span class="hide-menu">Manage Users</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="'.$CFG->wwwroot.'/user/editadvanced.php?id=-1&role=partner">&#128902;  My Labs</a></li>
                                    <li><a href="#">&#128902;  My lab feedbacks</a></li>
                                    <li><a href="#">&#128902;  My lab reports Group</a></li>
                                    <li><a href="#">&#128902;  Add Course Creator</a></li>

                                </ul>
                            </li>';
        $sidebar_account .='<li> 
                                <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-laptop-windows"></i><span class="hide-menu">Course Management</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="'.$CFG->wwwroot.'/course/subscribe_courses.php">&#128902;  Assign Courses</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/course/editcategory.php">&#128902;  Add Course Category</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/course/edit.php">&#128902;  Add Subscribed Course</a></li>
                                    <li><a href="#">&#128902;  Add Course Creator</a></li>
                                    <li><a href="#">&#128902;  Assign Course Creator</a></li>
                                    <li><a href="#">&#128902;  Subscribed Course List</a></li>

                                </ul>
                            </li>';
        $sidebar_account .='<li> 
                                <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-bullseye"></i><span class="hide-menu">Project Management</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="'.$CFG->wwwroot.'/local/eProjects/approved.php">&#128902;  All Projects</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/eProjects/project-attr.php">&#128902;  Project Attributes</a></li>

                                </ul>
                            </li>';
        $sidebar_account .='<li> 
                                <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-chart-bubble"></i><span class="hide-menu">Coupons</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="#">&#128902;  Partner Coupons</a></li>
                                    <li><a href="#">&#128902;  Training Partner Coupons</a></li>
                                </ul>
                            </li>';

        $sidebar_account .='<li> 
                                <a class=" waves-effect waves-dark" href="#" aria-expanded="false">
                                    <i class="mdi mdi-widgets"></i>
                                    <span class="hide-menu">Add to Subscription</span>
                                </a>
                            </li>';

        $sidebar_account .='<li> 
                                <a class=" waves-effect waves-dark" href="#" aria-expanded="false">
                                    <i class="mdi mdi-console"></i>
                                    <span class="hide-menu">Set eLabs Rule</span>
                                </a>
                            </li>'; 

        $sidebar_account .='<li> 
                                <a class="has-arrow waves-effect wavia-expanded="false"><i class="mdi mdi-arrange-send-backward"></i><span class="hide-menu">Video Conference</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="#">&#128902;  iKonnect Approvals</a></li>
                                    <li><a href="#">&#128902;  Zoom Meeting Approvals</a></li>
                                </ul>
                            </li>';

        $sidebar_account .='<li> 
                                <a class="has-arrow waves-effect wavia-expanded="false"><i class="mdi mdi-table"></i><span class="hide-menu">Common Functionality</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="#">&#128902;  3rd Party Assessments</a></li>
                                    <li><a href="#">&#128902;  Create eLabs</a></li>
                                    <li><a href="#">&#128902;  Upload Events</a></li>
                                    <li><a href="#">&#128902;  Upload iKonnect Sessions</a></li>
                                    <li><a href="#">&#128902;  Reset Or Switch Task</a></li>
                                </ul>
                            </li>';

        $sidebar_account .='<li> 
                                <a class="has-arrow waves-effect wavia-expanded="false"><i class="mdi mdi-email"></i><span class="hide-menu">Mentor Reports</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="#">&#128902;  Mentors List</a></li>
                                    <li><a href="#">&#128902;  Mentors Calendar</a></li>
                                    <li><a href="#">&#128902;  Mentorship Request by Student</a></li>
                                    <li><a href="#">&#128902;  Mentor Rescheduled Request</a></li>
                                    <li><a href="#">&#128902;  Mentor Accepted Request</a></li>
                                    <li><a href="#">&#128902;  Mentor Rejected Request</a></li>
                                </ul>
                            </li>';

        $sidebar_account .='<li> 
                                <a class="has-arrow waves-effect wavia-expanded="false"><i class="mdi mdi-book-open-variant"></i><span class="hide-menu">Reports</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="#">&#128902;  Consolidated</a></li>
                                    <li><a href="#">&#128902;  Register and Package Reports</a></li>
                                    <li><a href="#">&#128902;  TP Wise Login Reports</a></li>
                                    <li><a href="#">&#128902;  Consolidated Login Reports</a></li>
                                </ul>
                            </li>';
        return $sidebar_account;    
    }
    function partner_admin_menu(){
        global $DB,$CFG,$OUTPUT,$PAGE,$USER;
        $arr = $DB->get_record('partners',array('userid'=>$USER->id),'permission');
        $pa_permission = explode(',',$arr->permission);
        $sidebar_partner = '';
        if($pa_permission[0] == 1){
            $sidebar_partner .='<button class="accordion"><i class="fa fa-user"></i><span class="text">User Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="'.$CFG->wwwroot.'/user/editadvanced.php?id=-1">&#128902;  Create User</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/user/tpauserlist.php">&#128902;  User List</a></li>
                                </ul>
                            </div>';
        }
        if($pa_permission[1] == 1){
            $sidebar_partner .='<button class="accordion"><i class="fa fa-book"></i><span class="text">News Management</span></button>
                                <div class="panel">
                                    <ul>
                                        <li><a href="#">&#128902;  Upload News</a></li>
                                        <li><a href="#">&#128902;  Lists of News</a></li>
                                    </ul>
                                </div>';
        }
        if($pa_permission[2] == 1){
            $sidebar_partner .='<button class="accordion"><i class="fa fa-list-alt"></i><span class="text">Reports & Views</span></button>
                                <div class="panel">
                                    <ul>
                                        <li><a href="#">&#128902;  Summary Reports</a></li>
                                        <li><a href="#">&#128902;  Training Partners Reports</a></li>
                                        <li><a href="#">&#128902;  Hiring Company Reports</a></li>
                                        <li><a href="#">&#128902;  Usage Reports</a></li>
                                    </ul>
                                </div>';
        }
        if($pa_permission[3] == 1){
            $sidebar_partner .='';
        }   

        return $sidebar_partner;    
    }
    function training_partner_menu(){
        global $DB,$CFG,$OUTPUT,$PAGE,$USER;
        $creator = $DB->get_record('trainingpartners',array('userid'=>$USER->id),'createdby');
        $arr = $DB->get_record('partners',array('userid'=>$creator->createdby),'tp_permission');
        $tpa_permission = explode(',',$arr->tp_permission);
        $sidebar_training_partner = '';
        if($tpa_permission[0] == 1){
            $sidebar_training_partner .='<button class="accordion"><i class="fa fa-users"></i><span class="text">User Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="'.$CFG->wwwroot.'/user/tpauserlist.php">&#128902;  User List</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/admin/tool/uploaduser/index.php">&#128902;  User Bulk Upload</a></li>
                                </ul>
                            </div>';
        }
        if($tpa_permission[1] == 1){
            $sidebar_training_partner .='<button class="accordion"><i class="fa fa-btc"></i><span class="text">Batch Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="'.$CFG->wwwroot.'/local/course_batches/batch.php">&#128902;  Create Batch</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/course_batches/batch_enrolment.php">&#128902;  Add Students To Batch</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/course_batches/batchlist.php">&#128902;  Batch List</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/course_batches/migrate.php">&#128902;  Migrate Batch</a></li>
                                </ul>
                            </div>';
        }
        if($tpa_permission[2] == 1){
            $sidebar_training_partner .='<button class="accordion"><i class="fa fa-book"></i><span class="text">Course Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="'.$CFG->wwwroot.'/course/edit.php">&#128902;  Create Course</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/course/courselist.php">&#128902;  Course List</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/course_batches/batch_to_course.php">&#128902;  Assign Course To Batch</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/course_batches/course_to_professor.php">&#128902;  Assign Course To Professor</a></li>
                                </ul>
                            </div>';
        }   
        if($tpa_permission[3] == 1){
            $sidebar_training_partner .='<button class="accordion"><i class="fa fa-code-fork"></i><span class="text">Project Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Manage Component</a></li>
                                    <li><a href="#">&#128902;  Manage Project</a></li>
                                </ul>
                            </div>';
        }
        if($tpa_permission[4] == 1){
            $sidebar_training_partner .='<button class="accordion"><i class="fa fa-tasks"></i><span class="text">Task Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Manage Task</a></li>
                                    <li><a href="#">&#128902;  Task List</a></li>
                                    <li><a href="#">&#128902;  BulkUpload Task</a></li>
                                    <li><a href="#">&#128902;  BulkAssign Task</a></li>
                                </ul>
                            </div>';
        }
        if($tpa_permission[5] == 1){
            $sidebar_training_partner .='<button class="accordion"><i class="fa fa-database"></i><span class="text">Reports & Views</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Academic Reports</a></li>
                                    <li><a href="#">&#128902;  Academic Progress Reports</a></li>
                                    <li><a href="#">&#128902;  Non-Academic Reports</a></li>
                                    <li><a href="#">&#128902;  Non-Academic Progress Reports</a></li>
                                    <li><a href="#">&#128902;  Usage Reports</a></li>
                                </ul>
                            </div>';
        }
        if($tpa_permission[6] == 1){
            $sidebar_training_partner .='';
        }
        return $sidebar_training_partner;    
    }
    function professor_menu(){
        global $DB,$CFG,$OUTPUT,$PAGE,$USER;
        $sidebar_professors = '';
        if($DB->record_exists('tp_useruploads',array('userid'=>$USER->id))){
            $creator_tp = $DB->get_record('tp_useruploads',array('userid'=>$USER->id),'creatorid');
        }
        $creator_pa = $DB->get_record('trainingpartners',array('userid'=>$creator_tp->creatorid  ),'createdby');
        $arr = $DB->get_record('partners',array('userid'=>$creator_pa->createdby),'prof_permission');
        $prof_permission = explode(',',$arr->prof_permission);
        
        if($prof_permission[0] == 1){
            $sidebar_professors .='<button class="accordion"><i class="fa fa-search-plus"></i><span class="text">Batch Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Batch List</a></li>
                                    <li><a href="#">&#128902;  Record Attendance</a></li>
                                </ul>
                            </div>';
        }
        if($prof_permission[1] == 1){
            $sidebar_professors .='<li>
                                    <a class="" href="'.$CFG->wwwroot.'/my/courses.php">
                                        <i class="fa fa-book"></i>
                                        <span class="text">My Courses</span>
                                    </a>
                                </li>';
        }
        if($prof_permission[2] == 1){
            $sidebar_professors .='<button class="accordion"><i class="fa fa-laptop"></i><span class="text">My eLabs</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="'.$CFG->wwwroot.'/local/eLabs/addlabs.php">&#128902;  Add Labs</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/eLabs/assign_labs.php">&#128902;  Assign Labs</a></li>
                                    <li><a href="'.$CFG->wwwroot.'/local/eLabs/lablists.php">&#128902;  List of Labs</a></li>
                                    <li><a href="#">&#128902;  Evaluate Labs</a></li>
                                </ul>
                            </div>';
        }
        if($prof_permission[3] == 1){

            $manage_component = $CFG->wwwroot.'/local/eProjects/add_component.php';
            $manage_project = $CFG->wwwroot.'/local/eProjects/add_project.php';
            $project_request = $CFG->wwwroot.'/local/eProjects/request.php';
            $evaluate_project = $CFG->wwwroot.'/local/eProjects/evaluate.php';
            $sidebar_professors .='<button class="accordion"><i class="fa fa-code-fork"></i><span class="text">Project Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="'.$manage_component.'">&#128902;  Manage Component</a></li>
                                    <li><a href="'.$manage_project.'">&#128902;  Manage Project</a></li>
                                    <li><a href="'.$project_request.'">&#128902;  Project Request</a></li>
                                    <li><a href="'.$evaluate_project.'">&#128902;  Evaluate Project</a></li>
                                </ul>
                            </div>';
        }
        if($prof_permission[4] == 1){
            $sidebar_professors .='<li>
                                    <a class="" href="'.$CFG->wwwroot.'/my">
                                        <i class="fa fa-pencil-square-o"></i>
                                        <span class="text">My Assessments</span>
                                    </a>
                                </li>';
        }
        if($prof_permission[5] == 1){
            $sidebar_professors .='<button class="accordion"><i class="fa fa-tasks"></i><span class="text">Task Management</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  List of Tasks</a></li>
                                    <li><a href="#">&#128902;  Evaluate Tasks</a></li>
                                </ul>
                            </div>';
        }
        if($prof_permission[6] == 1){
            $sidebar_professors .='<button class="accordion"><i class="fa fa-comments-o"></i><span class="text">Video Conferences</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  iKonnect</a></li>
                                    <li><a href="#">&#128902;  Zoom</a></li>
                                </ul>
                            </div>';
        }
        if($prof_permission[7] == 1){
            $sidebar_professors .='<button class="accordion"><i class="fa fa-database"></i><span class="text">Reports</span></button>
                            <div class="panel">
                                <ul>
                                    <li><a href="#">&#128902;  Consolidated Report</a></li>
                                    <li><a href="#">&#128902;  Attendance Report</a></li>
                                    <li><a href="#">&#128902;  Prescribed Course Reports</a></li>
                                </ul>
                            </div>';
        }
        
        
        return $sidebar_professors;    
    }
    function student_menu(){
        global $DB,$CFG,$OUTPUT,$PAGE,$USER;
        $sidebar_students = '';
        $stud_permission = '';
        if($DB->record_exists('tp_useruploads',array('userid'=>$USER->id))){
            $creator_tp = $DB->get_record('tp_useruploads',array('userid'=>$USER->id),'creatorid');
            $creator_pa = $DB->get_record('trainingpartners',array('userid'=>$creator_tp->creatorid  ),'createdby');
            $arr = $DB->get_record('partners',array('userid'=>$creator_pa->createdby),'stud_permission');
            $stud_permission = explode(',',$arr->stud_permission);
        
            if($stud_permission[9] == 1){
                $sidebar_students ='<li> <a class=" waves-effect waves-dark" href="'.$CFG->wwwroot.'/my/account.php" aria-expanded="false"><i class="mdi mdi-widgets"></i><span class="hide-menu">My Account</span></a></li>';
            }
            if($stud_permission[0] == 1){
                $sidebar_students .='<li> <a class=" waves-effect waves-dark" href="'.$CFG->wwwroot.'/my/courses.php" aria-expanded="false"><i class="mdi mdi-book-open-variant"></i><span class="hide-menu">My courses</span></a></li>';

            }
            if($stud_permission[1] == 1){
                
                $sidebar_students .='<li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-laptop-windows"></i><span class="hide-menu">My eLabs</span></a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li><a href="'.$CFG->wwwroot.'/local/eLabs/myelabs.php">&#128902;  My Labs</a></li>
                                        <li><a href="#">&#128902;  My lab feedbacks</a></li>
                                        <li><a href="#">&#128902;  My lab reports Group</a></li>
                                    </ul>
                                </li>';
            }
            if($stud_permission[2] == 1){
                
                $sidebar_students .='<li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-bullseye"></i><span class="hide-menu">My eProjects</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="'.$CFG->wwwroot.'/local/eProjects/eprojects.php">&#128902;  My Projects</a></li>
                                <li><a href="'.$CFG->wwwroot.'/local/eProjects/group.php">&#128902;  Manage Group</a></li>
                                <li><a href="'.$CFG->wwwroot.'/local/eProjects/grouplist.php">&#128902;  Assign Group</a></li>
                                <li><a href="'.$CFG->wwwroot.'/local/eProjects/search-project.php">&#128902;  Search Projects</a></li>
                                <li><a href="'.$CFG->wwwroot.'/local/eProjects/accesspeer.php">&#128902;  Access Peers</a></li>
                            </ul>
                        </li>';
            }
            if($stud_permission[3] == 1){
                $sidebar_students .='<li> <a class=" waves-effect waves-dark" href="'.$CFG->wwwroot.'/my" aria-expanded="false"><i class="mdi mdi-email"></i><span class="hide-menu">My Assessments</span></a></li>';
            }
            if($stud_permission[4] == 1){
                $sidebar_students .='<li> <a class=" waves-effect waves-dark" href="'.$CFG->wwwroot.'/my" aria-expanded="false"><i class="mdi mdi-chart-bubble"></i><span class="hide-menu">My Tasks</span></a></li>';
            }
            if($stud_permission[8] == 1){
                $sidebar_students .='<li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-file"></i><span class="hide-menu">My Mentors</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="#">&#128902;  Search Mentors</a></li>
                                <li><a href="#">&#128902;  View Schedules</a></li>
                                <li><a href="#">&#128902;  Mentoring Feedback</a></li>
                            </ul>
                        </li>';
            }
            if($stud_permission[7] == 1){
                $sidebar_students .='<li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-table"></i><span class="hide-menu">Video Conferences</span></a>
                                    <ul aria-expanded="false" class="collapse">
                                        <li><a href="#">&#128902;  iKonnect</a></li>
                                        <li><a href="#">&#128902;  Zoom</a></li>
                                    </ul>
                                </li>';
            }
            if($stud_permission[6] == 1){
                $sidebar_students .= '<li> <a class=" waves-effect waves-dark" href="'.$CFG->wwwroot.'/my" aria-expanded="false"><i class="mdi mdi-widgets"></i><span class="hide-menu">Resume Analyzer</span></a>';
                
            }
            if($stud_permission[5] == 1){
                $sidebar_students .='<li> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-book-multiple"></i><span class="hide-menu">Job Bridge</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="#">&#128902;  Apply for Jobs</a></li>
                                <li><a href="#">&#128902;  Apply for Internships</a></li>
                                <li><a href="#">&#128902;  Career Guidance</a></li>
                                <li><a href="#">&#128902;  Resume Building</a></li>
                            </ul>
                        </li>';
            }
        }
        $sidebar_students .='<li> <a class=" waves-effect waves-dark" href="'.$CFG->wwwroot.'/my" aria-expanded="false"><i class="mdi mdi-book-open-variant"></i><span class="hide-menu">Higher Studies</span></a>';
        return $sidebar_students;    
    }
    function itrack_usermenu(){
        global $USER,$DB,$CFG;
        $menu = '';
        $menu .='<div class="dropdown1-content">
                    <a href="'.$CFG->wwwroot.'/user/profile.php?id='.$USER->id.'"><i class=" fa fa-suitcase"></i>Profile</a>
                    <a href="'.$CFG->wwwroot.'/admin/search.php"><i class="fa fa-cog"></i> Settings</a>
                    <a href="'.$CFG->wwwroot.'/login/logout.php?sesskey='.sesskey().'"><i class="fa fa-key"></i>Log out</a>
                </div>';
        return $menu;
    }
    function itrack_username(){
        global $USER,$DB,$CFG;
        $username = '';
        $user = $DB->get_record('user',array('id'=>$USER->id),'id,firstname,username');
        $username = $user->firstname;
        return $username;
    }

    function timeline($courseid){
        global $CFG,$PAGE,$DB;
        $course = $DB->get_record('course',array('id'=>$courseid));
        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();

        //echo html_writer::start_tag('div', array('class' => 'single-section'));

        $thissection = $modinfo->get_section_info(1);
        //print_object($modinfo->get_section_info(0));
        $sectiontitle = html_writer::tag('div', get_section_name($course, 1), array());
        $sectiontitle .= html_writer::end_tag('div');
        //echo $sectiontitle;

        $completioninfo = new completion_info($course);
        //echo $completioninfo->display_help_icon();
  
        //print_object($modinfo->get_section_info_all());
            $count = 0;
        foreach ($modinfo->get_section_info_all() as $sectionkey => $thissection) {
            
            $sectionreturn = null; $displayoptions = array();
            /*$ismoving = $this->page->user_is_editing() && ismoving($course->id);
            if ($ismoving) {
                $movingpix = new pix_icon('movehere', get_string('movehere'), 'moodle', array('class' => 'movetarget'));
                $strmovefull = strip_tags(get_string("movefull", "", "'$USER->activitycopyname'"));
            }*/

            $moduleshtml = array();
            if (!empty($modinfo->sections[$thissection->section])) {
                //echo $thissection->section;
                $count++;
                foreach ($modinfo->sections[$thissection->section] as $modnumber) {
                    $mod = $modinfo->cms[$modnumber];

                    /*if ($ismoving and $mod->id == $USER->activitycopy) {
                        // do not display moving mod
                        continue;
                    }*/

                    $all_sections[$sectionkey][]  = array('sectionid'=>$mod->get_section_info()->id,'sectionname'=>$mod->get_section_info()->name,'url'=>$mod->url,'type'=>$mod->modname,'modname'=>$mod->name);
                }
            }
        }
        return $all_sections;  
    }

    
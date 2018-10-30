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

class theme_itrackglobal_core_renderer extends core_renderer {

    public function notification($message, $classes = 'notifyproblem') {
        $message = clean_text($message);
        $type = '';

        if ($classes == 'notifyproblem') {
            $type = 'alert alert-error';
        }
        if ($classes == 'notifysuccess') {
            $type = 'alert alert-success';
        }
        if ($classes == 'notifymessage') {
            $type = 'alert alert-info';
        }
        if ($classes == 'redirectmessage') {
            $type = 'alert alert-block alert-info';
        }
        return "<div class=\"$type\">$message</div>";
    } 
    
    public function footer() {
        global $CFG, $DB, $USER;

        $output = $this->container_end_all(true);

        $footer = $this->opencontainers->pop('header/footer');

        if (debugging() and $DB and $DB->is_transaction_started()) {

        }

        $footer = str_replace($this->unique_end_html_token, $this->page->requires->get_end_code(), $footer);

        $this->page->set_state(moodle_page::STATE_DONE);

        if(!empty($this->page->theme->settings->persistentedit) && property_exists($USER, 'editing') && $USER->editing && !$this->really_editing) {
            $USER->editing = false;
        }

        return $output . $footer;
    }

    public function essentialblocks($region, $classes = array(), $tag = 'aside') {
        $classes = (array)$classes;
        $classes[] = 'block-region';
        $attributes = array(
            'id' => 'block-region-'.preg_replace('#[^a-zA-Z0-9_\-]+#', '-', $region),
            'class' => join(' ', $classes),
            'data-blockregion' => $region,
            'data-droptarget' => '1'
        );
        return html_writer::tag($tag, $this->blocks_for_region($region), $attributes);
    }
    
    public function edit_button(moodle_url $url) {
        $url->param('sesskey', sesskey());    
        if ($this->page->user_is_editing()) {
            $url->param('edit', 'off');
            $btn = 'btn-danger';
            $title = get_string('turneditingoff');
            $icon = 'fa-power-off';
        } else {
            $url->param('edit', 'on');
            $btn = 'btn-success';
            $title = get_string('turneditingon');
            $icon = 'fa-edit';
        }
        return html_writer::tag('a', html_writer::start_tag('i', array('class' => $icon.' fa fa-fw')).
         html_writer::end_tag('i'), array('href' => $url, 'title' => $title));
    }
    public function navbar() {
        $items = $this->page->navbar->get_items();
        if (empty($items)) { // MDL-46107.
            return '';
        }
        $breadcrumbs = '';
        foreach ($items as $item) {
            $item->hideicon = true;
            $breadcrumbs .= '<li>'.$this->render($item).'</li>';
        }

        $title = html_writer::tag('span', get_string('pagepath'), array('class' => 'accesshide', 'id' => 'navbar-label'));
        return $title.html_writer::start_tag('nav',
            array('aria-labelledby' => 'navbar-label',
                'aria-label' => 'breadcrumb',
                'class' => 'breadcrumb-nav',
                'role' => 'navigation')).
        html_writer::tag('ul', "$breadcrumbs", array('class' => 'breadcrumb')).
        html_writer::end_tag('nav');
    }
    public function block(block_contents $bc, $region) {
        $bc = clone($bc); // Avoid messing up the object passed in.
        if (empty($bc->blockinstanceid) || !strip_tags($bc->title)) {
            $bc->collapsible = block_contents::NOT_HIDEABLE;
        }
        if (!empty($bc->blockinstanceid)) {
            $bc->attributes['data-instanceid'] = $bc->blockinstanceid;
        }
        $skiptitle = strip_tags($bc->title);
        if ($bc->blockinstanceid && !empty($skiptitle)) {
            $bc->attributes['aria-labelledby'] = 'instance-'.$bc->blockinstanceid.'-header';
        } else if (!empty($bc->arialabel)) {
            $bc->attributes['aria-label'] = $bc->arialabel;
        }
        if ($bc->dockable) {
            $bc->attributes['data-dockable'] = 1;
        }
        if ($bc->collapsible == block_contents::HIDDEN) {
            $bc->add_class('hidden');
        }
        if (!empty($bc->controls)) {
            $bc->add_class('block_with_controls');
        }


        if (empty($skiptitle)) {
            $output = '';
            $skipdest = '';
        } else {
            $output = html_writer::link('#sb-'.$bc->skipid, get_string('skipa', 'access', $skiptitle),
                      array('class' => 'skip skip-block', 'id' => 'fsb-' . $bc->skipid));
            $skipdest = html_writer::span('', 'skip-block-to',
                      array('id' => 'sb-' . $bc->skipid));
        }

        $output .= html_writer::start_tag('div', $bc->attributes);
        $output .= html_writer::start_tag('li', array('class'=>'widget-cosllapsible'));
        $output .= $this->block_header($bc);
        $output .= $this->block_content($bc);
        $output .= html_writer::end_tag('li');
        $output .= html_writer::end_tag('div');

        $output .= $this->block_annotation($bc);

        $output .= $skipdest;

        $this->init_block_hider_js($bc);
        return $output;
    }

    public function block_header(block_contents $bc) {
        
        $blockname = explode('block',$bc->attributes['class']);
        $title = '';
        if ($bc->title) {
            $attributes = array();
            if ($bc->blockinstanceid) {
                $attributes['id'] = 'instance-'.$bc->blockinstanceid.'-header';
                $attributes['class'] = 'pull-left';

            }else{
                $attributes['class'] = 'pull-left';
            }
            $title = html_writer::tag('span', $bc->title, $attributes);
        }

        $blockid = null;
        if (isset($bc->attributes['id'])) {
            $blockid = $bc->attributes['id'];
        }
        $controlshtml = $this->block_controls($bc->controls, $blockid);

        $output = '';
        if ($title || $controlshtml) {
            //$output .= html_writer::tag('div', html_writer::tag('div', html_writer::tag('div', '', array('class'=>'block_action')). $title . $controlshtml, array('class' => 'title')), array('class' => 'header'));
            $output .= html_writer::tag('a',$title.
                html_writer::start_tag('span', array('class' => 'pull-right widget-collapse')).'<i class="ico-minus"></i>'.
                html_writer::end_tag('span'), 
                array('href' => '#', 'title' => $title,'class'=>'head widget-head active clearfix bg'.$blockname[1]));
        }
        return $output;
    }
    public function block_content(block_contents $bc) {
        //$output .= $bc->content;
        //$output .= $this->block_footer($bc);
        //$output = html_writer::start_tag('div', array('class' => 'content'));
        // if (!$bc->title && !$this->block_controls($bc->controls)) {
        //     $output .= html_writer::tag('div', '', array('class'=>'block_action notitle'));
        // }
        //$output .= $this->block_footer($bc);
        //$output .= html_writer::end_tag('div');
        $output = '';
        $output .= html_writer::start_tag('ul', array('class' => 'widget-container'));
        $output .= html_writer::start_tag('div', array('class' => 'prog-row side-mini-stat clearfix'));
        $output .= $bc->content;
        $output .= html_writer::end_tag('div');
        $output .= html_writer::end_tag('ul');
        return $output;
    }
}

include_once($CFG->dirroot . "/course/format/topics/renderer.php");

class theme_itrackglobal_format_topics_renderer extends format_topics_renderer {

    protected function get_nav_links($course, $sections, $sectionno) {
        $course = course_get_format($course)->get_course();
        $previousarrow= '<i class="fa fa-chevron-circle-left"></i>';
        $nextarrow= '<i class="fa fa-chevron-circle-right"></i>';
        $canviewhidden = has_capability('moodle/course:viewhiddensections', context_course::instance($course->id))
        or !$course->hiddensections;

        $links = array('previous' => '', 'next' => '');
        $back = $sectionno - 1;
        while ($back > 0 and empty($links['previous'])) {
            if ($canviewhidden || $sections[$back]->uservisible) {
                $params = array('id' => 'previous_section');
                if (!$sections[$back]->visible) {
                    $params = array('class' => 'dimmed_text');
                }
                $previouslink = html_writer::start_tag('div', array('class' => 'nav_icon'));
                $previouslink .= $previousarrow;
                $previouslink .= html_writer::end_tag('div');
                $previouslink .= html_writer::start_tag('span', array('class' => 'text'));
                $previouslink .= html_writer::start_tag('span', array('class' => 'nav_guide'));
                $previouslink .= get_string('previoussection', 'theme_itrackglobal');
                $previouslink .= html_writer::end_tag('span');
                $previouslink .= html_writer::empty_tag('br');
                $previouslink .= get_section_name($course, $sections[$back]);
                $previouslink .= html_writer::end_tag('span');
                $links['previous'] = html_writer::link(course_get_url($course, $back), $previouslink, $params);
            }
            $back--;
        }

        $forward = $sectionno + 1;
        while ($forward <= $course->numsections and empty($links['next'])) {
            if ($canviewhidden || $sections[$forward]->uservisible) {
                $params = array('id' => 'next_section');
                if (!$sections[$forward]->visible) {
                    $params = array('class' => 'dimmed_text');
                }
                $nextlink = html_writer::start_tag('div', array('class' => 'nav_icon'));
                $nextlink .= $nextarrow;
                $nextlink .= html_writer::end_tag('div');
                $nextlink .= html_writer::start_tag('span', array('class' => 'text'));
                $nextlink .= html_writer::start_tag('span', array('class' => 'nav_guide'));
                $nextlink .= get_string('nextsection', 'theme_itrackglobal');
                $nextlink .= html_writer::end_tag('span');
                $nextlink .= html_writer::empty_tag('br');
                $nextlink .= get_section_name($course, $sections[$forward]);
                $nextlink .= html_writer::end_tag('span');
                $links['next'] = html_writer::link(course_get_url($course, $forward), $nextlink, $params);
            }
            $forward++;
        }

        return $links;
    }
    
    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        global $PAGE;

        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();

        //echo html_writer::start_tag('div', array('class' => 'single-section'));

        $thissection = $modinfo->get_section_info($displaysection);
        //print_object($modinfo->get_section_info(0));
        $sectiontitle = html_writer::tag('div', get_section_name($course, $displaysection), array());
        $sectiontitle .= html_writer::end_tag('div');
        //echo $sectiontitle;

        $completioninfo = new completion_info($course);
        //echo $completioninfo->display_help_icon();
  
        //print_object($modinfo->get_section_info_all());
            $count = 0;
        foreach ($modinfo->get_section_info_all() as $sectionkey => $thissection) {
            
            $sectionreturn = null; $displayoptions = array();
            $ismoving = $this->page->user_is_editing() && ismoving($course->id);
            if ($ismoving) {
                $movingpix = new pix_icon('movehere', get_string('movehere'), 'moodle', array('class' => 'movetarget'));
                $strmovefull = strip_tags(get_string("movefull", "", "'$USER->activitycopyname'"));
            }

            $moduleshtml = array();
            if (!empty($modinfo->sections[$thissection->section])) {
                //echo $thissection->section;
                $count++;
                foreach ($modinfo->sections[$thissection->section] as $modnumber) {
                    $mod = $modinfo->cms[$modnumber];

                    if ($ismoving and $mod->id == $USER->activitycopy) {
                        // do not display moving mod
                        continue;
                    }

                    $all_sections[$sectionkey][]  = array($mod->get_section_info()->name,$mod->url);
                }
            }
        }  

        //print_object($all_sections);     
    }
    public function print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused) {
        global $PAGE;

        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();

        $context = context_course::instance($course->id);
        // Title with completion help icon.
        $completioninfo = new completion_info($course);
        echo $completioninfo->display_help_icon();
        echo $this->output->heading($this->page_title(), 2, 'accesshide');

        // Copy activity clipboard..
        echo $this->course_activity_clipboard($course, 0);

        // Now the list of sections..
        echo $this->start_section_list();
        $numsections = course_get_format($course)->get_last_section_number();

        $hasrow = false;
        foreach ($modinfo->get_section_info_all() as $section => $thissection) {
            if ($section == 0) {
                // 0-section is displayed a little different then the others.
                if ($thissection->summary or !empty($modinfo->sections[0]) or $PAGE->user_is_editing()) {
                    echo $this->section_header($thissection, $course, false, 0);
                    echo $this->courserenderer->course_section_cm_list($course, $thissection, 0);
                    echo $this->courserenderer->course_section_add_cm_control($course, 0, 0);
                    echo $this->section_footer();
                }
                continue;
            }
            if ($section > $numsections) {
                // Activities inside this section are 'orphaned', this section will be printed as 'stealth' below.
                continue;
            }

            if (!$hasrow && $course->coursedisplay) {
                // Initialize or reinitialize a new row to print the course sections boxes.
                echo "<div class='row'>";

                $hasrow = true;
            }

            // Show the section if the user is permitted to access it, OR if it's not available
            // but there is some available info text which explains the reason & should display.
            $showsection = $thissection->uservisible ||
                    ($thissection->visible && !$thissection->available &&
                    !empty($thissection->availableinfo));
            if (!$showsection) {
                // If the hiddensections option is set to 'show hidden sections in collapsed
                // form', then display the hidden section message - UNLESS the section is
                // hidden by the availability system, which is set to hide the reason.
                if (!$course->hiddensections && $thissection->available) {
                    echo $this->section_hidden($section, $course->id);
                }

                continue;
            }

            if (!$PAGE->user_is_editing() && $course->coursedisplay == COURSE_DISPLAY_MULTIPAGE) {
                // Display section summary only.
                echo $this->section_summary($thissection, $course, null);
            } else {
                echo $this->section_header($thissection, $course, false, 0);
                if ($thissection->uservisible) {
                    echo $this->courserenderer->course_section_cm_list($course, $thissection, 0);
                    echo $this->courserenderer->course_section_add_cm_control($course, $section, 0);
                }
                echo $this->section_footer();
            }

            if (!($section % 2) && $course->coursedisplay) {
                // There are at least 3 columns. So it's time to create a new row.
                $hasrow = false;

                echo "</div>";
            }
        }

        if ($hasrow && $course->coursedisplay) {
            // We need to close the opened row.
            echo "</div>";
        }

        if ($PAGE->user_is_editing() and has_capability('moodle/course:update', $context)) {
            // Print stealth sections if present.
            foreach ($modinfo->get_section_info_all() as $section => $thissection) {
                if ($section <= $numsections or empty($modinfo->sections[$section])) {
                    // This is not stealth section or it is empty.
                    continue;
                }
                echo $this->stealth_section_header($section);
                echo $this->courserenderer->course_section_cm_list($course, $thissection, 0);
                echo $this->stealth_section_footer();
            }

            echo $this->end_section_list();

            echo $this->change_number_sections($course, 0);
        } else {
            echo $this->end_section_list();
        }

    }

    public function course_section_cm_list($course, $section, $sectionreturn = null, $displayoptions = array()) {
        global $USER;
        echo 'arjun';
        $output = '';
        $modinfo = get_fast_modinfo($course);
        if (is_object($section)) {
            $section = $modinfo->get_section_info($section->section);
        } else {
            $section = $modinfo->get_section_info($section);
        }
        $completioninfo = new completion_info($course);

        // check if we are currently in the process of moving a module with JavaScript disabled
        $ismoving = $this->page->user_is_editing() && ismoving($course->id);
        if ($ismoving) {
            $movingpix = new pix_icon('movehere', get_string('movehere'), 'moodle', array('class' => 'movetarget'));
            $strmovefull = strip_tags(get_string("movefull", "", "'$USER->activitycopyname'"));
        }

        // Get the list of modules visible to user (excluding the module being moved if there is one)
        $moduleshtml = array();
        if (!empty($modinfo->sections[$section->section])) {
            foreach ($modinfo->sections[$section->section] as $modnumber) {
                $mod = $modinfo->cms[$modnumber];

                if ($ismoving and $mod->id == $USER->activitycopy) {
                    // do not display moving mod
                    continue;
                }

                if ($modulehtml = $this->course_section_cm_list_item($course,
                        $completioninfo, $mod, $sectionreturn, $displayoptions)) {
                    $moduleshtml[$modnumber] = $modulehtml;
                }
            }
        }

        $sectionoutput = '';
        if (!empty($moduleshtml) || $ismoving) {
            foreach ($moduleshtml as $modnumber => $modulehtml) {
                if ($ismoving) {
                    $movingurl = new moodle_url('/course/mod.php', array('moveto' => $modnumber, 'sesskey' => sesskey()));
                    $sectionoutput .= html_writer::tag('li',
                            html_writer::link($movingurl, $this->output->render($movingpix), array('title' => $strmovefull)),
                            array('class' => 'movehere'));
                }

                $sectionoutput .= $modulehtml;
            }

            if ($ismoving) {
                $movingurl = new moodle_url('/course/mod.php', array('movetosection' => $section->id, 'sesskey' => sesskey()));
                $sectionoutput .= html_writer::tag('li',
                        html_writer::link($movingurl, $this->output->render($movingpix), array('title' => $strmovefull)),
                        array('class' => 'movehere'));
            }
        }

        // Always output the section module list.
        $output .= html_writer::tag('ul', $sectionoutput, array('class' => 'section img-text'));

        return $output;
    }

    public function course_section_cm_list_item($course, &$completioninfo, cm_info $mod, $sectionreturn, $displayoptions = array()) {
        $output = '';
        if ($modulehtml = $this->course_section_cm($course, $completioninfo, $mod, $sectionreturn, $displayoptions)) {
            $modclasses = 'activity ' . $mod->modname . ' modtype_' . $mod->modname . ' ' . $mod->extraclasses;
            $output .= html_writer::tag('li', $modulehtml, array('class' => $modclasses, 'id' => 'module-' . $mod->id));
        }
        return $output;
    }

    public function course_section_cm($course, &$completioninfo, cm_info $mod, $sectionreturn, $displayoptions = array()) {
        $output = '';
        // We return empty string (because course module will not be displayed at all)
        // if:
        // 1) The activity is not visible to users
        // and
        // 2) The 'availableinfo' is empty, i.e. the activity was
        //     hidden in a way that leaves no info, such as using the
        //     eye icon.
        if (!$mod->is_visible_on_course_page()) {
            return $output;
        }

        $indentclasses = 'mod-indent';
        if (!empty($mod->indent)) {
            $indentclasses .= ' mod-indent-'.$mod->indent;
            if ($mod->indent > 15) {
                $indentclasses .= ' mod-indent-huge';
            }
        }

        $output .= html_writer::start_tag('div');

        if ($this->page->user_is_editing()) {
            $output .= course_get_cm_move($mod, $sectionreturn);
        }

        $output .= html_writer::start_tag('div', array('class' => 'mod-indent-outer'));

        // This div is used to indent the content.
        $output .= html_writer::div('', $indentclasses);

        // Start a wrapper for the actual content to keep the indentation consistent
        $output .= html_writer::start_tag('div');

        // Display the link to the module (or do nothing if module has no url)
        $cmname = $this->course_section_cm_name($mod, $displayoptions);

        if (!empty($cmname)) {
            // Start the div for the activity title, excluding the edit icons.
            $output .= html_writer::start_tag('div', array('class' => 'activityinstance'));
            $output .= $cmname;


            // Module can put text after the link (e.g. forum unread)
            $output .= $mod->afterlink;

            // Closing the tag which contains everything but edit icons. Content part of the module should not be part of this.
            $output .= html_writer::end_tag('div'); // .activityinstance
        }

        // If there is content but NO link (eg label), then display the
        // content here (BEFORE any icons). In this case cons must be
        // displayed after the content so that it makes more sense visually
        // and for accessibility reasons, e.g. if you have a one-line label
        // it should work similarly (at least in terms of ordering) to an
        // activity.
        $contentpart = $this->course_section_cm_text($mod, $displayoptions);
        $url = $mod->url;
        if (empty($url)) {
            $output .= $contentpart;
        }

        $modicons = '';
        if ($this->page->user_is_editing()) {
            $editactions = course_get_cm_edit_actions($mod, $mod->indent, $sectionreturn);
            $modicons .= ' '. $this->course_section_cm_edit_actions($editactions, $mod, $displayoptions);
            $modicons .= $mod->afterediticons;
        }

        $modicons .= $this->course_section_cm_completion($course, $completioninfo, $mod, $displayoptions);

        if (!empty($modicons)) {
            $output .= html_writer::span($modicons, 'actions');
        }

        // Show availability info (if module is not available).
        $output .= $this->course_section_cm_availability($mod, $displayoptions);

        // If there is content AND a link, then display the content here
        // (AFTER any icons). Otherwise it was displayed before
        if (!empty($url)) {
            $output .= $contentpart;
        }

        $output .= html_writer::end_tag('div'); // $indentclasses

        // End of indentation div.
        $output .= html_writer::end_tag('div');

        $output .= html_writer::end_tag('div');
        return $output;
    }

    public function course_section_cm_name(cm_info $mod, $displayoptions = array()) {
        if (!$mod->is_visible_on_course_page() || !$mod->url) {
            // Nothing to be displayed to the user.
            return '';
        }

        list($linkclasses, $textclasses) = $this->course_section_cm_classes($mod);
        $groupinglabel = $mod->get_grouping_label($textclasses);

        // Render element that allows to edit activity name inline. It calls {@link course_section_cm_name_title()}
        // to get the display title of the activity.
        $tmpl = new \core_course\output\course_module_name($mod, $this->page->user_is_editing(), $displayoptions);
        return $this->output->render_from_template('core/inplace_editable', $tmpl->export_for_template($this->output)) .
            $groupinglabel;
    }
     protected function course_section_cm_classes(cm_info $mod) {
        $linkclasses = '';
        $textclasses = '';
        if ($mod->uservisible) {
            $conditionalhidden = $this->is_cm_conditionally_hidden($mod);
            $accessiblebutdim = (!$mod->visible || $conditionalhidden) &&
                has_capability('moodle/course:viewhiddenactivities', $mod->context);
            if ($accessiblebutdim) {
                $linkclasses .= ' dimmed';
                $textclasses .= ' dimmed_text';
                if ($conditionalhidden) {
                    $linkclasses .= ' conditionalhidden';
                    $textclasses .= ' conditionalhidden';
                }
            }
            if ($mod->is_stealth()) {
                // Stealth activity is the one that is not visible on course page.
                // It still may be displayed to the users who can manage it.
                $linkclasses .= ' stealth';
                $textclasses .= ' stealth';
            }
        } else {
            $linkclasses .= ' dimmed';
            $textclasses .= ' dimmed dimmed_text';
        }
        return array($linkclasses, $textclasses);
    }

    public function course_section_cm_text(cm_info $mod, $displayoptions = array()) {
        $output = '';
        if (!$mod->is_visible_on_course_page()) {
            // nothing to be displayed to the user
            return $output;
        }
        $content = $mod->get_formatted_content(array('overflowdiv' => true, 'noclean' => true));
        list($linkclasses, $textclasses) = $this->course_section_cm_classes($mod);
        if ($mod->url && $mod->uservisible) {
            if ($content) {
                // If specified, display extra content after link.
                $output = html_writer::tag('div', $content, array('class' =>
                        trim('contentafterlink ' . $textclasses)));
            }
        } else {
            $groupinglabel = $mod->get_grouping_label($textclasses);

            // No link, so display only content.
            $output = html_writer::tag('div', $content . $groupinglabel,
                    array('class' => 'contentwithoutlink ' . $textclasses));
        }
        return $output;
    }
    protected function is_cm_conditionally_hidden(cm_info $mod) {
        global $CFG;
        $conditionalhidden = false;
        if (!empty($CFG->enableavailability)) {
            $info = new \core_availability\info_module($mod);
            $conditionalhidden = !$info->is_available_for_all();
        }
        return $conditionalhidden;
    }

    public function course_section_cm_completion($course, &$completioninfo, cm_info $mod, $displayoptions = array()) {
        global $CFG, $DB;
        $output = '';
        if (!empty($displayoptions['hidecompletion']) || !isloggedin() || isguestuser() || !$mod->uservisible) {
            return $output;
        }
        if ($completioninfo === null) {
            $completioninfo = new completion_info($course);
        }
        $completion = $completioninfo->is_enabled($mod);
        if ($completion == COMPLETION_TRACKING_NONE) {
            if ($this->page->user_is_editing()) {
                $output .= html_writer::span('&nbsp;', 'filler');
            }
            return $output;
        }

        $completiondata = $completioninfo->get_data($mod, true);
        $completionicon = '';

        if ($this->page->user_is_editing()) {
            switch ($completion) {
                case COMPLETION_TRACKING_MANUAL :
                    $completionicon = 'manual-enabled'; break;
                case COMPLETION_TRACKING_AUTOMATIC :
                    $completionicon = 'auto-enabled'; break;
            }
        } else if ($completion == COMPLETION_TRACKING_MANUAL) {
            switch($completiondata->completionstate) {
                case COMPLETION_INCOMPLETE:
                    $completionicon = 'manual-n' . ($completiondata->overrideby ? '-override' : '');
                    break;
                case COMPLETION_COMPLETE:
                    $completionicon = 'manual-y' . ($completiondata->overrideby ? '-override' : '');
                    break;
            }
        } else { // Automatic
            switch($completiondata->completionstate) {
                case COMPLETION_INCOMPLETE:
                    $completionicon = 'auto-n' . ($completiondata->overrideby ? '-override' : '');
                    break;
                case COMPLETION_COMPLETE:
                    $completionicon = 'auto-y' . ($completiondata->overrideby ? '-override' : '');
                    break;
                case COMPLETION_COMPLETE_PASS:
                    $completionicon = 'auto-pass'; break;
                case COMPLETION_COMPLETE_FAIL:
                    $completionicon = 'auto-fail'; break;
            }
        }
        if ($completionicon) {
            $formattedname = $mod->get_formatted_name();
            if ($completiondata->overrideby) {
                $args = new stdClass();
                $args->modname = $formattedname;
                $overridebyuser = \core_user::get_user($completiondata->overrideby, '*', MUST_EXIST);
                $args->overrideuser = fullname($overridebyuser);
                $imgalt = get_string('completion-alt-' . $completionicon, 'completion', $args);
            } else {
                $imgalt = get_string('completion-alt-' . $completionicon, 'completion', $formattedname);
            }

            if ($this->page->user_is_editing()) {
                // When editing, the icon is just an image.
                $completionpixicon = new pix_icon('i/completion-'.$completionicon, $imgalt, '',
                        array('title' => $imgalt, 'class' => 'iconsmall'));
                $output .= html_writer::tag('span', $this->output->render($completionpixicon),
                        array('class' => 'autocompletion'));
            } else if ($completion == COMPLETION_TRACKING_MANUAL) {
                $newstate =
                    $completiondata->completionstate == COMPLETION_COMPLETE
                    ? COMPLETION_INCOMPLETE
                    : COMPLETION_COMPLETE;
                // In manual mode the icon is a toggle form...

                // If this completion state is used by the
                // conditional activities system, we need to turn
                // off the JS.
                $extraclass = '';
                if (!empty($CFG->enableavailability) &&
                        core_availability\info::completion_value_used($course, $mod->id)) {
                    $extraclass = ' preventjs';
                }
                $output .= html_writer::start_tag('form', array('method' => 'post',
                    'action' => new moodle_url('/course/togglecompletion.php'),
                    'class' => 'togglecompletion'. $extraclass));
                $output .= html_writer::start_tag('div');
                $output .= html_writer::empty_tag('input', array(
                    'type' => 'hidden', 'name' => 'id', 'value' => $mod->id));
                $output .= html_writer::empty_tag('input', array(
                    'type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()));
                $output .= html_writer::empty_tag('input', array(
                    'type' => 'hidden', 'name' => 'modulename', 'value' => $mod->name));
                $output .= html_writer::empty_tag('input', array(
                    'type' => 'hidden', 'name' => 'completionstate', 'value' => $newstate));
                $output .= html_writer::tag('button',
                    $this->output->pix_icon('i/completion-' . $completionicon, $imgalt), array('class' => 'btn btn-link'));
                $output .= html_writer::end_tag('div');
                $output .= html_writer::end_tag('form');
            } else {
                // In auto mode, the icon is just an image.
                $completionpixicon = new pix_icon('i/completion-'.$completionicon, $imgalt, '',
                        array('title' => $imgalt));
                $output .= html_writer::tag('span', $this->output->render($completionpixicon),
                        array('class' => 'autocompletion'));
            }
        }
        return $output;
    }

    public function course_section_cm_availability(cm_info $mod, $displayoptions = array()) {
        global $CFG;
        $output = '';
        if (!$mod->is_visible_on_course_page()) {
            return $output;
        }
        if (!$mod->uservisible) {
            // this is a student who is not allowed to see the module but might be allowed
            // to see availability info (i.e. "Available from ...")
            if (!empty($mod->availableinfo)) {
                $formattedinfo = \core_availability\info::format_info(
                        $mod->availableinfo, $mod->get_course());
                $output = $this->availability_info($formattedinfo, 'isrestricted');
            }
            return $output;
        }
        // this is a teacher who is allowed to see module but still should see the
        // information that module is not available to all/some students
        $modcontext = context_module::instance($mod->id);
        $canviewhidden = has_capability('moodle/course:viewhiddenactivities', $modcontext);
        if ($canviewhidden && !$mod->visible) {
            // This module is hidden but current user has capability to see it.
            // Do not display the availability info if the whole section is hidden.
            if ($mod->get_section_info()->visible) {
                $output .= $this->availability_info(get_string('hiddenfromstudents'), 'ishidden');
            }
        } else if ($mod->is_stealth()) {
            // This module is available but is normally not displayed on the course page
            // (this user can see it because they can manage it).
            $output .= $this->availability_info(get_string('hiddenoncoursepage'), 'isstealth');
        }
        if ($canviewhidden && !empty($CFG->enableavailability)) {
            // Display information about conditional availability.
            // Don't add availability information if user is not editing and activity is hidden.
            if ($mod->visible || $this->page->user_is_editing()) {
                $hidinfoclass = 'isrestricted isfullinfo';
                if (!$mod->visible) {
                    $hidinfoclass .= ' hide';
                }
                $ci = new \core_availability\info_module($mod);
                $fullinfo = $ci->get_full_information();
                if ($fullinfo) {
                    $formattedinfo = \core_availability\info::format_info(
                            $fullinfo, $mod->get_course());
                    $output .= $this->availability_info($formattedinfo, $hidinfoclass);
                }
            }
        }
        return $output;
    }

    public function availability_info($text, $additionalclasses = '') {

        $data = ['text' => $text, 'classes' => $additionalclasses];
        $additionalclasses = array_filter(explode(' ', $additionalclasses));

        if (in_array('ishidden', $additionalclasses)) {
            $data['ishidden'] = 1;

        } else if (in_array('isstealth', $additionalclasses)) {
            $data['isstealth'] = 1;

        } else if (in_array('isrestricted', $additionalclasses)) {
            $data['isrestricted'] = 1;

            if (in_array('isfullinfo', $additionalclasses)) {
                $data['isfullinfo'] = 1;
            }
        }

        return $this->render_from_template('core/availability_info', $data);
    }


    protected function section_summary($section, $course, $mods) {
        $classattr = 'section main section-summary clearfix col-md-6 col-xs-12';
        $linkclasses = '';

        // If section is hidden then display grey section link.
        if (!$section->visible) {
            $classattr .= ' hidden';
            $linkclasses .= ' dimmed_text';
        } else if (course_get_format($course)->is_section_current($section)) {
            $classattr .= ' current';
        }

        $title = get_section_name($course, $section);
        $output = '';
        $output .= html_writer::start_tag('li', array('id' => 'section-'.$section->section,
            'class' => $classattr, 'role' => 'region', 'aria-label' => $title));

        $output .= html_writer::start_tag('div', array('class' => 'card card-block'));

        $output .= html_writer::tag('div', '<i class="fa fa-laptop"></i>', array('class' => 'pgr-img'));

        $output .= html_writer::start_tag('div', array('class' => 'pgr-content'));
        $output .= html_writer::tag('div', '', array('class' => 'left side'));
        $output .= html_writer::tag('div', '', array('class' => 'right side'));
        $output .= html_writer::start_tag('div', array('class' => 'content'));

        if ($section->uservisible) {
            $title = html_writer::tag('a', $title,
                array('href' => course_get_url($course, $section->section), 'class' => $linkclasses));
        }

        $output .= $this->output->heading($title, 4, 'section-title');

        $output .= html_writer::start_tag('div', array('class' => 'summarytext'));
        $output .= $this->format_summary_text($section);
        $output .= html_writer::end_tag('div');
        $output .= $this->section_activity_summary($section, $course, null);

        $context = context_course::instance($course->id);
        $output .= $this->section_availability_message($section,
            has_capability('moodle/course:viewhiddensections', $context));

        $output .= html_writer::end_tag('div'); // Content.
        $output .= html_writer::end_tag('div'); // Col-md-9.
        $output .= html_writer::end_tag('div'); // Pgr-content.
        $output .= html_writer::end_tag('li');

        return $output;
    }

    /**
     * Generate a summary of the activites in a section
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course the course record from DB
     * @param array    $mods (argument not used)
     * @return string HTML to output.
     */
    protected function section_activity_summary($section, $course, $mods) {
        $modinfo = get_fast_modinfo($course);
        if (empty($modinfo->sections[$section->section])) {
            return '';
        }

        // Generate array with count of activities in this section.
        $sectionmods = array();
        $total = 0;
        $complete = 0;
        $cancomplete = isloggedin() && !isguestuser();
        $completioninfo = new completion_info($course);
        foreach ($modinfo->sections[$section->section] as $cmid) {
            $thismod = $modinfo->cms[$cmid];

            if ($thismod->uservisible) {
                if (isset($sectionmods[$thismod->modname])) {
                    $sectionmods[$thismod->modname]['name'] = $thismod->modplural;
                    $sectionmods[$thismod->modname]['count']++;
                } else {
                    $sectionmods[$thismod->modname]['name'] = $thismod->modfullname;
                    $sectionmods[$thismod->modname]['count'] = 1;
                }
                if ($cancomplete && $completioninfo->is_enabled($thismod) != COMPLETION_TRACKING_NONE) {
                    $total++;
                    $completiondata = $completioninfo->get_data($thismod, true);
                    if ($completiondata->completionstate == COMPLETION_COMPLETE ||
                            $completiondata->completionstate == COMPLETION_COMPLETE_PASS) {
                        $complete++;
                    }
                }
            }
        }

        if (empty($sectionmods)) {
            // No sections.
            return '';
        }

        // Output section completion data.
        $output = '';
        if ($total > 0) {
            $completion = new stdClass;
            $completion->complete = $complete;
            $completion->total = $total;

            $percent = 0;
            if ($complete > 0) {
                $percent = (int) (($complete / $total) * 100);
            }

            $output = html_writer::start_tag('div', array('class' => 'section-summary-activities'));
            $output .= html_writer::tag(
                'span',
                get_string('discipline_progress', 'theme_moove'),
                array('class' => 'activity-count')
            );
            $output .= "<div class='progress'>";
            $output .= "<div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='{$percent}' ";
            $output .= " aria-valuemin='0' aria-valuemax='100' style='width: {$percent}%;'>";
            $output .= "{$percent}%";
            $output .= "</div>";
            $output .= "</div>";
            $output .= html_writer::end_tag('div');
        }

        return $output;
    }
    public function box_start($classes = 'generalbox222', $id = null, $attributes = array()) {
        $this->opencontainers->push('box', html_writer::end_tag('div'));
        $attributes['id'] = $id;
        $attributes['class'] = 'box ' . renderer_base::prepare_classes($classes);
        return html_writer::start_tag('div', $attributes);
    }
}

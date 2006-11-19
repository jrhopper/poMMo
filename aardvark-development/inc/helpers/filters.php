<?php
/** [BEGIN HEADER] **
 * COPYRIGHT: (c) 2006 Brice Burgess / All Rights Reserved    
 * LICENSE: http://www.gnu.org/copyleft.html GNU/GPL 
 * AUTHOR: Brice Burgess <bhb@iceburg.net>
 * SOURCE: http://pommo.sourceforge.net/
 *
 *  :: RESTRICTIONS ::
 *  1. This header must accompany all portions of code contained within.
 *  2. You must notify the above author of modifications to contents within.
 * 
 ** [END HEADER]**/

class PommoFilter {
	
	// returns the legal(logical) group selections for new filters 
	// accepts a group object
	// accepts an array of all groups
	// returns array of group names. Array key correlates to group's ID
	function & getLegalGroups(&$group, &$groups) {
		$o = array();
		
		foreach($groups as $id => $g) {
			if($g['name'] != $group['name'])
				$o[$id] = $g['name'];
		}
		
		foreach($group['criteria'] as $c) {
			if ($c['logic'] == 'is_in' || $c['logic'] == 'not_in')
				unset($o[$c['value']]);
		}
		
		return $o;
		
	}
	
	// returns the legal(logical) selections for new filters based off pre-existing criteria
	// accepts a list of groups
	// accepts a list of fields
	// returns an array of field names. Array key correlates to field_id.
	function & getLegalFilters(&$groups, &$fields) {
		$c = array();
		
		$legalities = array(
			'checkbox' => array('true','false'),
			'multiple' => array('is','not'),
			'text' => array('is','not'),
			'date' => array('is','not','greater','less'),
			'number' => array('is','not','greater','less')
		);
		
		foreach ($fields as $field)
			$c[$field['id']] = $legalities[$field['type']];
		
		// subtract illogical selections from $c
		foreach ($groups as $group)
			foreach ($group['criteria'] as $criteria) {
				
				// create reference to this field's legalities 
				$l =& $c[$criteria['field_id']];
				
				switch($criteria['logic']) {
					case 'true' :
					case 'false' :
						// if criteria is true or false, field cannot be ANYTHING else
						unset($l[array_search('true', $l)]);
						unset($l[array_search('false', $l)]);
						break;
					case 'is' :
					case 'not' :
						unset($l[array_search('not', $l)]);
						unset($l[array_search('is', $l)]);
						break;
					case 'greater' :
						unset($l[array_search('greater', $l)]);
						break;
					case 'less':
						unset($l[array_search('less', $l)]);
						break;
				}
			}
		
		foreach($c as $key => $val) {
			if (empty($val))
				unset($c[$key]);
			else
				$c[$key] = $fields[$key]['name'];
		}

		return $c;
	}
	
	// gets the legal (logical) logic dropdowns for a field
	// accepts a groub object
	// accepts a field object
	// returns an array whose key is logic, value is "english" logic
	function & getLogic(&$group, &$field) {
		
		$o = array();
		
		$legalities = array(
			'checkbox' => array('true','false'),
			'multiple' => array('is','not'),
			'text' => array('is','not'),
			'date' => array('is','not','greater','less'),
			'number' => array('is','not','greater','less')
		);
		
		$english = array(
			'is' => Pommo::_T('is'),
			'not' => Pommo::_T('is not'),
			'true' => Pommo::_T('is checked'),
			'false' => Pommo::_T('is not checked'),
			'greater' => Pommo::_T('is greater than'),
			'less' => Pommo::_T('is less than')
		);
		
		$a = $legalities[$field['type']];
		// subtract illogical selections from $c
		foreach ($group['criteria'] as $criteria) {
			// create reference to this field's legalities 
			if($criteria['field_id'] == $field['id']) {
				switch($criteria['logic']) {
					case 'true' :
					case 'false' :
						// if criteria is true or false, field cannot be ANYTHING else
						$a = array();
						break;
					case 'is' :
						unset($a[array_search('not', $a)]);
						unset($a[array_search('is', $a)]);
						break;
					case 'not' :
						unset($a[array_search('is', $a)]);
						unset($a[array_search('not', $a)]);
						break;
					case 'greater' :
						unset($a[array_search('greater', $a)]);
						break;
					case 'less':
						unset($a[array_search('less', $a)]);
						break;
				}
			}
		}
		
		foreach($a as $logic) {
			$o[$logic] = $english[$logic];
		}
		return $o;
	}
	
	function addBoolFilter(&$group, &$match, &$logic) {
		global $pommo;
		$dbo =& $pommo->_dbo;
		
		$query = "
			INSERT INTO " . $dbo->table['group_criteria']."
			SET
				group_id=%i,
				field_id=%i,
				logic='%s'";
		$query=$dbo->prepare($query,array($group,$match,$logic));
		return $dbo->affected($query);
	}
	
	function addGroupFilter(&$group, &$match, &$logic) {
		global $pommo;
		$dbo =& $pommo->_dbo;
		
		$query = "
			INSERT INTO " . $dbo->table['group_criteria']."
			SET
				group_id=%i,
				value=%i,
				logic='%s'";
		$query=$dbo->prepare($query,array($group,$match,$logic));
		return $dbo->affected($query);
	}
	
	function addFieldFilter(&$group, &$match, &$logic, &$values) {
		global $pommo;
		$dbo =& $pommo->_dbo;
		
		foreach($values as $value)
			$v[] = $dbo->prepare("(%i,%i,'%s','%s')",array($group, $match, $logic, $value));
			
		$query = "
			INSERT INTO " . $dbo->table['group_criteria']."
			(group_id, field_id, logic, value)
			VALUES ".implode(',', $v);
		echo $query;
		return $dbo->affected($query);
	}
}
?>
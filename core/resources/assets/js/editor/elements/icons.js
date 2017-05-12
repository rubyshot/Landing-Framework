/*
  Generate semi-unique class
  to reference icons for use in the editor. Add `-clone`
  suffix to class to prevent cloning to the power and
  link dropdown to icon (Tether).
*/

function lfInitIcon($icon, unique_class) {
  var timestamp = new Date().getTime();

  if (typeof unique_class === 'undefined') {

    // Set unique class
    var unique_class = '-x-data-icon-' + timestamp;

    // Clone edit button
    var $edit_button = $(xTplIconButton).clone().appendTo('body');
  } else {
    var icon_class = unique_class;

    // New unique class
    var unique_class = '-x-data-icon-' + timestamp;

    // Clone icon and replace with new class
    $icon.removeClass(icon_class);
    $icon.addClass(unique_class);
    $icon.attr('data-x-el', unique_class);

    // Settings
    var $edit_button = $('.-x-el-icon-edit-clone[data-x-el=' + icon_class + ']').clone().insertAfter('.-x-el-icon-edit-clone[data-x-el=' + icon_class + ']');
    $edit_button.attr('data-x-el', unique_class);
  }

  // Attribute settings
  var attachment = $icon.attr('data-attachment');
  attachment = (typeof attachment !== typeof undefined && attachment !== false) ? attachment : 'top left';

  var targetAttachment = $icon.attr('data-target-attachment');
  targetAttachment = (typeof targetAttachment !== typeof undefined && targetAttachment !== false) ? targetAttachment : 'bottom left';

  var offset = $icon.attr('data-offset');
  offset = (typeof offset !== typeof undefined && offset !== false) ? offset : '0 0';

  var dropdownPosition = $icon.attr('data-dropdown-position');
  dropdownPosition = (typeof dropdownPosition !== typeof undefined && dropdownPosition !== false) ? dropdownPosition : 'right';

  $icon.addClass(unique_class);
  $icon.attr('data-x-el', unique_class);
  $edit_button.attr('data-x-el', unique_class);

  // Set reference to parent block
  $edit_button.attr('data-x-parent-block', $icon.parents('.-x-block').attr('data-x-el'));

  // Replace class so it won't be cloned in next loop
  $edit_button.removeClass('-x-el-icon-edit').addClass('-x-el-icon-edit-clone -x-el-inline-button-clone');

  // Add class so dropdown opens on the left side
  if (dropdownPosition == 'left') {
    $edit_button.find('.-x-el-dropdown').addClass('-x-el-dropdown-left');
  }

  new Tether({
    element: $edit_button,
    target: $icon,
    attachment: attachment,
    offset: offset,
    targetAttachment: targetAttachment,
    classPrefix: '-x-data',
    constraints: [{
      to: 'scrollParent',
      attachment: 'together'
    }],
    optimizations: {
      moveElement: true,
      gpu: true
    }
  });
}

function lfInitIcons() {

  /*
    Loop through all icons
  */

  $('.-x-icon').each(function() {
    var $icon = $(this);
    lfInitIcon($icon);
  });

  lfParseIcons(true);

  /* 
    Open modal to configure icon
  */

  $('body').on('click', '.-x-el-icon-edit', function() {
    var el_class = $(this).parents('.-x-el-icon-edit-clone').attr('data-x-el');

    if (! $(this).hasClass('-x-el-disabled') && typeof el_class !== typeof undefined && el_class !== false) {

      // Hide dropdown after option has been clicked
      $(this).parents('.-x-el-dropdown').trigger('mouseleave');

      // Check what settings can be configured in the modal
      //var $el = $('.' + el_class);

      lfOpenModal(_lang["url"] + '/landingpages/editor/modal/icon', el_class);
    }
  });
}

/* 
  Duplicate icons and references
*/

function lfDuplicateBlockIcons($new_block) {

  // Loop through all icons in new block
  $new_block.find('.-x-icon').each(function() {
    var timestamp = new Date().getTime();
    var $new_icon = $(this);
    var icon_class = $new_icon.attr('data-x-el');

    lfInitIcon($new_icon, icon_class);
  });

  // Timeout to make sure dom has changed
  setTimeout(lfParseIcons, 70);
}

/* 
  Loop through icon settings to set attributes
  and fix z-index overlapping. 
*/

function lfParseIcons(init) {
  var zIndex = 200;
  
  $('.-x-icon').each(function() {
    var icon_class = $(this).attr('data-x-el');
    var $icon_settings = $('.-x-el-icon-edit-clone[data-x-el=' + icon_class + ']');

    // Set z-index to prevent overlapping of dropdown menus
    $icon_settings.css('cssText', 'z-index: ' + zIndex + ' !important;');
    $icon_settings.find('.-x-el-dropdown').css('cssText', 'z-index: ' + zIndex + ' !important;');
    zIndex--;
  });

  // Always reposition tethered elements.
  // Also initially because $icon_settings.css('cssText', ...); 
  // seems to reset position
  Tether.position();
}
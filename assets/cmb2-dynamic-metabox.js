(function ($) {
  // Make sure that metaboxStates is not empty
  if (typeof(metaboxStates) == 'undefined' || ! metaboxStates || metaboxStates instanceof Array) {
    metaboxStates = {};
  }

  let post = $('#post');
  let input = $('<input type="hidden" name="metabox-states" value="'+JSON.stringify(metaboxStates)+'">');
  post.prepend(input);

  $('.dynamic-metabox').each(function(index) {
    // Find elements
    let metabox = $(this).closest('.cmb2-postbox');
    let id = metabox.attr('id');
    let handle = metabox.find('.hndle');
    let handleButton = metabox.find('.handlediv');

    // Create checkbox and add it
    let checkbox = '<input type="checkbox" id="switch-'+id+'" data-target="#'+id+'" class="metabox-switch">';
    let slider = '<label for="switch-'+id+'" class="metabox-switch">Toggle</label>';
    let toggleSwitch = $(checkbox+slider);
    handle.prepend(toggleSwitch);

    // Handlers
    let preventCollapse = function(event) {
      event.stopImmediatePropagation();
    };

    let updateState = function(state) {
      metaboxStates[id] = state;
      input.attr('value', JSON.stringify(metaboxStates));
    };

    let switchOff = function() {
      updateState(false);

      // Hide and prevent the box from being opened
      metabox.addClass('closed');
      handleButton.hide();
      handle.onFirst('click', preventCollapse);
    };

    let switchOn = function() {
      updateState(true);

      // Show the button
      handleButton.show();
      handle.off('click', preventCollapse);
    };

    // Initialize state
    if (typeof metaboxStates[id] !== 'undefined') {
      // Set state from saved value
      if (metaboxStates[id]) {
        switchOn();
        toggleSwitch.prop('checked', true);
      }
      else {
        switchOff();
      }
    }
    else {
      // If it's a new box with no value set yet we disable it by default
      switchOff();
    }

    // Main (when the switch is clicked)
    toggleSwitch.on('change', function(event) {
      if ($(this).is(':checked')) {
        switchOn();
      } else {
        switchOff();
      }
    });
  });
})(jQuery);

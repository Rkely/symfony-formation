$('#add-image').click(function(){
    const index = +$('#widget-counters').val();
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);
    $('#ad_images').append(tmpl);
    $('#widget-counters').val(index + 1);
    suppchamp();
  });
   
  function suppchamp(){
      $('button[data-action="Delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
      });
  }
  function upadatecounter(){
      const count = +$('ad_images div.form-group').length;
      $('#widgets-counter').val(count);
  }
  upadatecounter();
  suppchamp();
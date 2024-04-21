/* eslint-env browser */
(function() {
  'use strict';
  document.addEventListener('DOMContentLoaded', function() {
    // Your custom JavaScript goes here
    $("select").select2();
    const table =  $(".js-table").DataTable({
      searching: true,
      info: false,
      order: [],
      responsive: true,
      language: {
        paginate: {
          previous:
            '<span class="prev-icon paginate_button"><svg xmlns="http://www.w3.org/2000/svg" width="4" height="7" fill="none"><path fill="#5B5B5B" d="M3.948 6.328a.175.175 0 0 1 0 .248l-.37.371a.168.168 0 0 1-.246 0L.116 3.731a.262.262 0 0 1-.077-.186v-.09c0-.07.028-.137.077-.186L3.332.053a.168.168 0 0 1 .245 0l.371.371a.175.175 0 0 1 0 .248L1.121 3.5l2.827 2.828Z"/></svg></span>',
          next: '<span class="next-icon paginate_button"><svg xmlns="http://www.w3.org/2000/svg" width="4" height="7" fill="none"><path fill="#5B5B5B" d="M.052.672a.175.175 0 0 1 0-.248l.37-.371a.168.168 0 0 1 .246 0l3.216 3.216c.049.05.077.116.077.186v.09c0 .07-.028.137-.077.186L.668 6.947a.168.168 0 0 1-.245 0l-.371-.371a.175.175 0 0 1 0-.248L2.879 3.5.052.672Z"/></svg></span>',
        },
      },
    });

    const gatewayRows = document.querySelectorAll('.js-table-gateway tbody tr:not(.disabled)');
    if(gatewayRows){
        gatewayRows.forEach(row => {
            row.addEventListener('click', function() {
                const radio = row.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                }
            });
        });
    }

    const searchInput  = document.querySelector('#searchGateway');
    if(searchInput){
        searchInput.addEventListener('keyup', function() {
            table.search(this.value).draw();
        });
    }
    // steps
      const steps = document.querySelectorAll('.s-nav--steps li');
      steps.forEach(step => {
          step.addEventListener('click', function() {
             const href= step.lastChild.getAttribute('href');
             if(href) window.location.href=href
          });
      });

    // handle step 2
    const radioButtons = document.querySelectorAll('td input[type="radio"]');
    radioButtons.forEach(radio => {
      radio.addEventListener('change', function() {
        // Reset all rows
        const rows = document.querySelectorAll('.c-table.js-table tbody tr');
        rows.forEach(row => {
          row.classList.remove('selected-row');
        });

        // Set background color for the selected row
        const selectedRow = this.closest('tr');
        selectedRow.classList.add('selected-row');

        // Update button text and enable it
        const button = document.querySelector('.c-form__footer input[type="submit"]');
        button.value = 'Continue';
        button.removeAttribute('disabled');
      });
    });

  });
})();

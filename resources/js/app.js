import Fuse from 'fuse.js';
import 'alpinejs';

function navHeader() {
  return {
    initSearch() {
      fetch(`/storage/index.json`)
        .then((response) => {
          return response.json();
        }).then((json) => {
          const fuse = new Fuse(json, {
            keys: ['title'],
            shouldSort: true
          });
        });
    },
    updateSearch(event) {
      debugger;
    }
  };
};
// $('#search').on('keyup', function () {
//   let result = fuse.search($(this).val());

//   // Output it
//   let resultdiv = $('ul.searchresults');
//   if (result.length === 0) {
//     // Hide results
//     resultdiv.hide();
//   } else {
//     // Show results
//     resultdiv.empty();
//     for (let item in result.slice(0,4)) {
//       let searchitem = '<li><a href="' + result[item].url + '">' + result[item].title + '</a></li>';
//       resultdiv.append(searchitem);
//     }
//     resultdiv.show();
//   }
// });

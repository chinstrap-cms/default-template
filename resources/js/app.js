import Fuse from 'fuse.js';
import 'alpinejs';

window.navHeader = function () {
  return {
    initSearch() {
      fetch(`/storage/index.json`)
        .then((response) => {
          return response.json();
        }).then((json) => {
          window.fuse = new Fuse(json, {
            keys: ['title'],
            shouldSort: true
          });
        });
    },
    updateSearch(event) {
      const value = event.currentTarget.value;
      if (value.length === 0) {
        this.items = [];
      }
      const result = window.fuse.search(value);
      this.items = result.map(item => item.item);
    },
    items: [],
  };
};

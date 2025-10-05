document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.querySelector('.search-field');
  const suggestionsBox = document.getElementById('search-suggestions');
  const resultsContainer = suggestionsBox.querySelector('.search-results-content');

  let timer;

  if (searchInput) {
    searchInput.addEventListener('input', function () {
      clearTimeout(timer);

      const query = this.value.trim();
      if (query.length < 2) {
        suggestionsBox.style.display = 'none';
        return;
      }

      timer = setTimeout(() => {
        fetch(`${liveSearch.ajax_url}?action=live_search_suggestions&term=${encodeURIComponent(query)}`)
          .then(res => res.json())
          .then(data => {
            resultsContainer.innerHTML = '';

            if (data.length) {
              data.forEach(item => {
                const el = document.createElement('div');
                el.className = 'search-suggestion-item';
                el.innerHTML = `
                  <a href="${item.url}">
                    ${item.thumbnail ? `<img src="${item.thumbnail}" class="search-thumb">` : ''}
                    <span class="search-title">${item.title}</span>
                  </a>`;
                resultsContainer.appendChild(el);
              });
              suggestionsBox.style.display = 'block';
            } else {
              suggestionsBox.style.display = 'none';
            }
          });
      }, 250);
    });

    document.addEventListener('click', function (e) {
      if (!suggestionsBox.contains(e.target) && e.target !== searchInput) {
        suggestionsBox.style.display = 'none';
      }
    });
  }
});

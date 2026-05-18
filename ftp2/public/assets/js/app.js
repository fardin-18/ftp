document.addEventListener('DOMContentLoaded', function () {
    var registerForm = document.getElementById('registerForm');

    if (registerForm) {
        registerForm.addEventListener('submit', function (event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirmPassword').value;

            if (password.length < 8) {
                alert('Password must be at least 8 characters.');
                event.preventDefault();
            }

            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                event.preventDefault();
            }
        });
    }

    var contentForm = document.getElementById('contentForm');

    if (contentForm) {
        contentForm.addEventListener('submit', function (event) {
            var title = contentForm.querySelector('[name="title"]').value.trim();
            var category = contentForm.querySelector('[name="category_id"]').value;

            if (title === '' || category === '') {
                alert('Title and category are required.');
                event.preventDefault();
            }
        });
    }

    var searchForm = document.getElementById('searchForm');

    if (searchForm) {
        searchForm.addEventListener('submit', function (event) {
            event.preventDefault();

            var q = document.getElementById('searchInput').value;
            var categoryId = document.getElementById('categoryFilter').value;
            var url = 'index.php?controller=home&action=search&q=' + encodeURIComponent(q) + '&category_id=' + encodeURIComponent(categoryId);

            fetch(url)
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    var list = document.getElementById('contentList');
                    list.innerHTML = '';

                    if (!data.contents || data.contents.length === 0) {
                        list.innerHTML = '<p>No content found.</p>';
                        return;
                    }

                    data.contents.forEach(function (item) {
                        var card = document.createElement('article');
                        card.className = 'content-card';

                        card.innerHTML =
                            '<h3>' + escapeHtml(item.title) + '</h3>' +
                            '<p>' + escapeHtml(item.description || '') + '</p>' +
                            '<p><strong>Category:</strong> ' + escapeHtml(item.category_name || '') + '</p>' +
                            '<p><strong>Downloads:</strong> ' + escapeHtml(item.download_count || '0') + '</p>' +
                            '<a class="button" href="index.php?controller=home&action=download&id=' + item.id + '">Download</a>';

                        list.appendChild(card);
                    });
                });
        });
    }

    var requestForm = document.getElementById('requestForm');

    if (requestForm) {
        requestForm.addEventListener('submit', function (event) {
            event.preventDefault();

            var title = requestForm.querySelector('[name="content_title"]').value.trim();

            if (title === '') {
                alert('Content title is required.');
                return;
            }

            fetch(requestForm.action, {
                method: 'POST',
                body: new FormData(requestForm)
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    var message = document.getElementById('requestMessage');
                    message.textContent = data.message || 'Done.';

                    if (data.success) {
                        requestForm.reset();
                    }
                })
                .catch(function () {
                    alert('Request failed.');
                });
        });
    }

    var statusDropdowns = document.querySelectorAll('.request-status');

    statusDropdowns.forEach(function (select) {
        select.addEventListener('change', function () {
            var formData = new FormData();
            formData.append('id', this.dataset.id);
            formData.append('status', this.value);
            formData.append('csrf_token', this.dataset.token);

            fetch(this.dataset.url, {
                method: 'POST',
                body: formData
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (!data.success) {
                        alert('Could not update status.');
                    }
                })
                .catch(function () {
                    alert('Could not update status.');
                });
        });
    });
});

function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

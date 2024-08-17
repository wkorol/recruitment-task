<?php

declare(strict_types=1);

require_once __DIR__ . '/auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="resources/styles.css">
</head>
<body>
<div class="dashboard-container">
    <img src="resources/svg/logo.svg" alt="Logo" class="logo">
    <div id="success-message" class="success-message" style="display: none;"></div>
    <div class="news-section">
        <h2>All News</h2>
        <div id="news-list">
        </div>
    </div>

    <div class="create-news-section">
        <div class="form-header">
            <h2 id="form-heading">Create News</h2>
            <button type="button" class="icon-button close-edit-button" id="close-edit-button" style="display: none;">
                <img src="resources/svg/close.svg" alt="Close" class="icon">
            </button>
        </div>
        <form id="create-news-form" class="create-news-form">
            <input type="text" name="title" placeholder="Title" class="input-field" required>
            <textarea name="description" placeholder="Description" class="input-field textarea-field" required></textarea>
            <button type="submit" class="action-button" id="form-submit-button">Create</button>
        </form>
        <div id="form-feedback" class="feedback-message"></div>
    </div>

    <form id="logout-form" method="POST">
        <button type="submit" class="action-button">Logout</button>
    </form>
</div>

<script>
    let isEditMode = false;
    let editNewsId = null;

    async function loadNews() {
        try {
            const response = await fetch('/news');
            const newsItems = await response.json();

            const newsList = document.getElementById('news-list');
            const newsSection = document.querySelector('.news-section');

            newsList.innerHTML = '';

            if (newsItems.length === 0) {
                newsSection.style.display = 'none';
            } else {
                newsSection.style.display = 'block';

                newsItems.forEach(news => {
                    const newsItem = document.createElement('div');
                    newsItem.className = 'news-item';

                    const newsTitle = document.createElement('div');
                    newsTitle.className = 'news-title';
                    newsTitle.textContent = news.title;

                    const newsDescription = document.createElement('div');
                    newsDescription.className = 'news-description';
                    newsDescription.textContent = news.description;

                    const newsActions = document.createElement('div');
                    newsActions.className = 'news-actions';

                    const editButton = document.createElement('button');
                    editButton.className = 'icon-button edit-button';
                    editButton.innerHTML = '<img src="resources/svg/pencil.svg" alt="Edit" class="icon">';
                    editButton.addEventListener('click', () => enterEditMode(news));

                    const deleteButton = document.createElement('button');
                    deleteButton.className = 'icon-button delete-button';
                    deleteButton.innerHTML = '<img src="resources/svg/close.svg" alt="Delete" class="icon">';
                    deleteButton.addEventListener('click', () => deleteNews(news.id));

                    newsActions.appendChild(editButton);
                    newsActions.appendChild(deleteButton);

                    newsItem.appendChild(newsTitle);
                    newsItem.appendChild(newsDescription);
                    newsItem.appendChild(newsActions);

                    newsList.appendChild(newsItem);
                });
            }

        } catch (error) {
            console.error('Error loading news:', error);
        }
    }

    function enterEditMode(news) {
        isEditMode = true;
        editNewsId = news.id;

        document.querySelector('input[name="title"]').value = news.title;
        document.querySelector('textarea[name="description"]').value = news.description;

        document.getElementById('form-heading').textContent = 'Edit News';
        document.getElementById('form-submit-button').textContent = 'Save';

        document.getElementById('close-edit-button').style.display = 'inline-block';
    }

    function exitEditMode() {
        isEditMode = false;
        editNewsId = null;

        document.querySelector('input[name="title"]').value = '';
        document.querySelector('textarea[name="description"]').value = '';

        document.getElementById('form-heading').textContent = 'Create News';
        document.getElementById('form-submit-button').textContent = 'Create';

        document.getElementById('close-edit-button').style.display = 'none';
    }

    async function createOrUpdateNews(event) {
        event.preventDefault();

        const form = event.target;
        const title = form.title.value;
        const description = form.description.value;

        const feedback = document.getElementById('form-feedback');
        const successMessage = document.getElementById('success-message');

        feedback.textContent = '';
        successMessage.style.display = 'none';

        try {
            let response;
            if (isEditMode && editNewsId !== null) {
                response = await fetch(`/news/${editNewsId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        title: title,
                        description: description,
                    }),
                });
            } else {
                response = await fetch('/news', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        title: title,
                        description: description,
                    }),
                });
            }

            const result = await response.json();
            console.log('Operation result:', result);

            await loadNews();

            form.reset();

            successMessage.textContent = isEditMode ? 'News was successfully updated!' : 'News was successfully created!';
            successMessage.style.display = 'block';

            exitEditMode();

        } catch (error) {
            console.error('Error saving news:', error);
        }
    }

    async function deleteNews(id) {
        try {
            const response = await fetch(`/news/${id}`, {
                method: 'DELETE',
            });

            if (!response.ok) {
                const errorMessage = await response.text();
                throw new Error(errorMessage || 'Failed to delete news');
            }

            const result = await response.json();
            console.log('News deleted:', result);

            await loadNews();

            const successMessage = document.getElementById('success-message');
            successMessage.textContent = 'News was successfully deleted!';
            successMessage.style.display = 'block';

        } catch (error) {
            console.error('Error deleting news:', error);
            alert('Failed to delete news. Please try again.');
        }
    }

    async function logout(event) {
        event.preventDefault();

        try {
            const response = await fetch('/logout', {
                method: 'POST',
            });

            if (response.ok) {
                window.location.href = '/login.php';
            } else {
                const errorMessage = await response.text();
                throw new Error(errorMessage);
            }

        } catch (error) {
            console.error('Error logging out:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', loadNews);
    document.getElementById('create-news-form').addEventListener('submit', createOrUpdateNews);
    document.getElementById('close-edit-button').addEventListener('click', exitEditMode);
    document.getElementById('logout-form').addEventListener('submit', logout);

</script>
</body>
</html>

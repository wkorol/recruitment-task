<?php

declare(strict_types=1);
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

    <div class="news-section">
        <div id="success-message" class="success-message" style="display: none;"></div>
        <h2>All News</h2>
        <div id="news-list">
        </div>
    </div>


    <div class="create-news-section">
        <h2>Create News</h2>
        <form id="create-news-form" class="create-news-form">
            <input type="text" name="title" placeholder="Title" class="input-field" required>
            <textarea name="description" placeholder="Description" class="input-field textarea-field" required></textarea>
            <button type="submit" class="action-button">Create</button>
        </form>
        <div id="form-feedback" class="feedback-message"></div>
    </div>

    <!-- Logout Button -->
    <form action="logout.php" method="POST">
        <button type="submit" class="action-button">Logout</button>
    </form>
</div>

<script>
    async function loadNews() {
        try {
            const response = await fetch('/news');

            const newsItems = await response.json();

            const newsList = document.getElementById('news-list');

            newsList.innerHTML = '';

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

                const deleteButton = document.createElement('button');
                deleteButton.className = 'icon-button delete-button';
                deleteButton.innerHTML = '<img src="resources/svg/close.svg" alt="Delete" class="icon">';

                newsActions.appendChild(editButton);
                newsActions.appendChild(deleteButton);

                newsItem.appendChild(newsTitle);
                newsItem.appendChild(newsDescription);
                newsItem.appendChild(newsActions);

                newsList.appendChild(newsItem);
            });

        } catch (error) {
            console.error('Error loading news:', error);
        }
    }

    async function createNews(event) {
        event.preventDefault();


        const form = event.target;
        const title = form.title.value;
        const description = form.description.value;

        const feedback = document.getElementById('form-feedback');
        const successMessage = document.getElementById('success-message');

        feedback.textContent = '';
        successMessage.style.display = 'none';

        try {
            const response = await fetch('/news', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    title: title,
                    description: description,
                }),
            });

            const result = await response.json();
            console.log('News created:', result);

            await loadNews();

            form.reset();

            successMessage.textContent = 'News was successful created!';
            successMessage.style.display = 'block';

        } catch (error) {
            console.error('Error creating news:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', loadNews);
    
    document.getElementById('create-news-form').addEventListener('submit', createNews);
</script>
</body>
</html>

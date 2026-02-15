<?php include 'home-header.php'; ?>

<main>
    <div class="search-container">
        <input type="text" id="searchBox" class="search-box" placeholder="Search..." onkeyup="searchFunction()" onkeypress="handleKeyPress(event)">
        <div id="suggestionBox" class="suggestions"></div>

        <div class="categories">
            <div class="category active" onclick="setCategory('all', event)">All</div>
            <div class="category" onclick="setCategory('posts', event)">Posts</div>
            <div class="category" onclick="setCategory('videos', event)">Videos</div>
            <div class="category" onclick="setCategory('users', event)">Users</div>
        </div>

        <div id="loading" class="loading" style="display: none;">🔄 Searching...</div>
        <div id="searchResults"></div>

        <div id="recentSearchesContainer">
            <h3>Recent Searches</h3>
            <ul id="recentSearchesList"></ul>
        </div>
    </div>

<script>
    let currentCategory = 'all';

    // **Category Change Function**
    function setCategory(category, event) {
        currentCategory = category;
        document.querySelectorAll('.category').forEach(cat => cat.classList.remove('active'));
        event.target.classList.add('active');
        searchFunction(); // Call search again when category changes
    }

    // **Search on Enter Key**
    function handleKeyPress(event) {
        if (event.key === "Enter") {
            searchFunction();
        }
    }

// Live Search Function
function searchFunction() {
    let query = document.getElementById('searchBox').value.trim();
    if (query.length < 2) return;

    document.getElementById('loading').style.display = 'block';

    fetch(`/ajay/home/search_backend.php?query=${query}&category=${currentCategory}`)
    .then(response => response.json())
    .then(data => {
        let resultsDiv = document.getElementById('searchResults');
        resultsDiv.innerHTML = '';

        if (data.length === 0) {
            resultsDiv.innerHTML = "<p>No results found.</p>";
        }

        data.forEach((item, index) => {
            let div = document.createElement('div');
            div.style.padding = '10px';
            div.style.borderRadius = '5px';
            div.style.transition = 'background 0.3s ease';
            div.style.marginTop = '10px'; // ✅ Top margin added

            if (item.type === 'user') {
                div.innerHTML = `<b>User:</b> <a href="/ajay/profile/profile.php?user_id=${item.user_id}" 
                                  style="color: blue; text-decoration: underline; cursor: pointer; background: transparent; display: inline-block;">
                                  ${item.username}</a>`;  // ✅ Background transparent
            } else if (item.type === 'post') {
                div.innerHTML = `<b>Post:</b> <a href="/ajay/home/post-detail.php?id=${item.post_id}" 
                                  style="color: green; text-decoration: underline; cursor: pointer;">
                                  ${item.title}</a>`;
            } else if (item.type === 'video') {
                div.innerHTML = `<b>Video:</b> <a href="/ajay/home/video-detail.php?id=${item.post_id}" 
                                  style="color: red; text-decoration: underline; cursor: pointer;">
                                  ${item.title}</a>`;
            }
            resultsDiv.appendChild(div);
        });

        document.getElementById('loading').style.display = 'none';

        // **Store Recent Search**
        storeRecentSearch(query);
    })
    .catch(() => {
        document.getElementById('loading').style.display = 'none';
    });
}


    // **Store Recent Searches in Local Storage**
    function storeRecentSearch(query) {
        let searches = JSON.parse(localStorage.getItem("recentSearches")) || [];
        if (!searches.includes(query)) {
            searches.unshift(query);
            if (searches.length > 5) searches.pop(); // Max 5 recent searches
            localStorage.setItem("recentSearches", JSON.stringify(searches));
        }
        showRecentSearches();
    }

    // **Show Recent Searches**
    function showRecentSearches() {
        let searches = JSON.parse(localStorage.getItem("recentSearches")) || [];
        let list = document.getElementById("recentSearchesList");
        list.innerHTML = "";
        searches.forEach(search => {
            let li = document.createElement("li");
            li.textContent = search;
            li.style.cursor = 'pointer';
            li.style.padding = '5px';
            li.style.borderBottom = '1px solid #ddd';
            li.onmouseover = () => li.style.background = '#f0f0f0';
            li.onmouseout = () => li.style.background = 'transparent';
            li.onclick = () => {
                document.getElementById("searchBox").value = search;
                searchFunction();
            };
            list.appendChild(li);
        });
    }

    // **Hide Suggestions on Outside Click**
    document.addEventListener("click", function (event) {
        if (!event.target.closest(".search-container")) {
            document.getElementById("suggestionBox").style.display = "none";
        }
    });

    // **Call on Page Load**
    showRecentSearches();
</script>

</main>

<?php include 'home-footer.php'; ?>

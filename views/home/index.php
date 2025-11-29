<div class="dashboard-container">
    <!-- Sidebar / History -->
    <aside class="history-sidebar">
        <h3>Recent Comics</h3>
        <div class="history-list">
            <?php if (empty($recentComics)): ?>
                <p class="empty-state">No comics yet.</p>
            <?php else: ?>
                <?php foreach ($recentComics as $comic): ?>
                    <a href="/comic/<?php echo $comic['id']; ?>" class="history-item">
                        <div class="history-thumb"
                            style="background-image: url('<?php echo htmlspecialchars($comic['first_panel'] ?? ''); ?>')"></div>
                        <div class="history-info">
                            <span
                                class="history-prompt"><?php echo htmlspecialchars(substr($comic['prompt'], 0, 30)) . '...'; ?></span>
                            <span class="history-date"><?php echo date('M d', strtotime($comic['created_at'])); ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </aside>

    <!-- Main Creation Area -->
    <section class="creation-area">
        <div class="creation-header">
            <h2>Create New Comic</h2>
            <p>Describe your story and let AI do the magic.</p>
        </div>

        <form id="create-form" class="creation-form">
            <div class="form-group">
                <div class="label-row" style="display: flex; justify-content: space-between; align-items: center;">
                    <label for="prompt" class="form-label">Story Prompt</label>
                    <button type="button" id="enhance-btn" class="btn-text">
                        <span class="material-symbols-outlined" style="font-size: 1.2rem;">auto_fix</span>
                        Enhance Prompt
                    </button>
                </div>
                <textarea id="prompt" name="prompt" class="form-input" rows="4"
                    placeholder="Enter your story idea here... e.g. A robot discovers a flower in a post-apocalyptic city."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="style" class="form-label">Art Style</label>
                    <div class="select-wrapper">
                        <select id="style" name="style" class="form-select">
                            <option value="Modern">Modern Comic</option>
                            <option value="Manga">Manga / Anime</option>
                            <option value="Noir">Film Noir</option>
                            <option value="Classic">Classic Superhero</option>
                            <option value="Watercolor">Watercolor</option>
                        </select>
                        <span class="material-symbols-outlined select-icon">expand_more</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="panel_count" class="form-label">Panels (Optional, Max 10)</label>
                    <input type="number" id="panel_count" name="panel_count" class="form-input" min="1" max="10"
                        placeholder="Auto">
                </div>
                <div class="form-group">
                    <label for="layout" class="form-label">Layout</label>
                    <div class="select-wrapper">
                        <select id="layout" name="layout" class="form-select">
                            <option value="3-panel">3-Panel Strip</option>
                            <option value="4-panel">4-Panel Grid</option>
                            <option value="6-panel">6-Panel Page</option>
                        </select>
                        <span class="material-symbols-outlined select-icon">expand_more</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-large btn-generate">
                <span class="material-symbols-outlined">auto_awesome</span>
                Generate Comic
            </button>
        </form>

        <!-- Result / Preview Area -->
        <div id="result-area" class="result-area hidden">
            <div class="result-header">
                <h3>Generated Comic</h3>
                <div class="result-actions">
                    <button class="btn-secondary" id="download-btn">
                        <span class="material-symbols-outlined">download</span> Download PDF
                    </button>
                </div>
            </div>

            <!-- Multi-Panel Display -->
            <div class="comic-panels-container" id="comic-panels-container">
                <!-- Panels will be inserted here by JavaScript -->
            </div>

            <!-- Collapsible Script Section -->
            <details class="script-dropdown">
                <summary class="script-dropdown-header">
                    <h4>View Script</h4>
                    <span class="material-symbols-outlined dropdown-icon">expand_more</span>
                </summary>
                <div class="script-content" id="generated-script"></div>
            </details>
        </div>

        <!-- Loading State -->
        <div id="loading-state" class="loading-state hidden">
            <div class="spinner"></div>
            <p>Generating your masterpiece... This may take a moment.</p>
            <small class="loading-tip">Tip: Each panel is generated individually for best quality!</small>
        </div>
    </section>
</div>
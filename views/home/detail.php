<div class="dashboard-container">
    <!-- Sidebar / History -->
    <aside class="history-sidebar">
        <h3>Recent Comics</h3>
        <div class="history-list">
            <?php if (empty($recentComics)): ?>
                <p class="empty-state">No comics yet.</p>
            <?php else: ?>
                <?php foreach ($recentComics as $recentComic): ?>
                    <a href="/comic/<?php echo $recentComic['id']; ?>" class="history-item">
                        <div class="history-thumb"
                            style="background-image: url('<?php echo htmlspecialchars($recentComic['first_panel'] ?? ''); ?>')">
                        </div>
                        <div class="history-info">
                            <span
                                class="history-prompt"><?php echo htmlspecialchars(substr($recentComic['prompt'], 0, 30)) . '...'; ?></span>
                            <span class="history-date"><?php echo date('M d', strtotime($recentComic['created_at'])); ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </aside>

    <!-- Main Content Area -->
    <section class="creation-area">
        <div class="creation-header">
            <h2>Comic Details</h2>
            <p><?php echo htmlspecialchars($comic['prompt']); ?></p>
        </div>

        <div class="result-area">
            <div class="result-header">
                <h3>Generated Comic</h3>
                <div class="result-actions">
                    <button class="btn-secondary" id="download-btn-detail">
                        <span class="material-symbols-outlined">download</span> Download PDF
                    </button>
                </div>
            </div>

            <!-- Multi-Panel Display -->
            <div class="comic-panels-container">
                <?php if (!empty($panels)): ?>
                    <?php foreach ($panels as $panel): ?>
                        <div class="comic-panel">
                            <img src="<?php echo htmlspecialchars($panel['image_path']); ?>"
                                alt="Panel <?php echo $panel['panel_order']; ?>" class="panel-img">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-state">No panels found for this comic.</p>
                <?php endif; ?>
            </div>

            <!-- Collapsible Script Section -->
            <details class="script-dropdown">
                <summary class="script-dropdown-header">
                    <h4>View Script</h4>
                    <span class="material-symbols-outlined dropdown-icon">expand_more</span>
                </summary>
                <div class="script-content" id="detail-script">
                    <?php
                    if (!empty($comic['script_text'])) {
                        echo $comic['script_text'];
                    } else {
                        echo "**Prompt:** " . htmlspecialchars($comic['prompt']) . "\n\n*This comic was created before scripts were saved. Only the original prompt is available.*";
                    }
                    ?>
                </div>
            </details>

            <div class="comic-meta">
                <div class="meta-row">
                    <strong>Style:</strong> <?php echo htmlspecialchars($comic['style']); ?>
                </div>
                <div class="meta-row">
                    <strong>Layout:</strong> <?php echo htmlspecialchars($comic['layout']); ?>
                </div>
                <div class="meta-row">
                    <strong>Panels:</strong> <?php echo count($panels); ?>
                </div>
                <div class="meta-row">
                    <strong>Created:</strong> <?php echo date('F j, Y \a\t g:i A', strtotime($comic['created_at'])); ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Render markdown if available
    document.addEventListener('DOMContentLoaded', () => {
        const scriptEl = document.getElementById('detail-script');
        if (scriptEl && typeof marked !== 'undefined') {
            const scriptText = scriptEl.textContent;
            scriptEl.innerHTML = marked.parse(scriptText);
        }

        // PDF Download with multiple panels
        const downloadBtn = document.getElementById('download-btn-detail');
        if (downloadBtn) {
            downloadBtn.addEventListener('click', async () => {
                try {
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF({
                        orientation: 'portrait',
                        unit: 'mm',
                        format: 'a4'
                    });

                    pdf.setFontSize(20);
                    pdf.setFont(undefined, 'bold');
                    pdf.text('ComicAI Generated Comic', 105, 20, { align: 'center' });

                    let yPosition = 35;
                    const panelImages = document.querySelectorAll('.panel-img');

                    for (let i = 0; i < panelImages.length; i++) {
                        const img = panelImages[i];

                        if (img && img.src) {
                            const canvas = await html2canvas(img, {
                                scale: 2,
                                useCORS: true,
                                allowTaint: true
                            });
                            const imgData = canvas.toDataURL('image/png');

                            const imgWidth = 170;
                            const imgHeight = (canvas.height * imgWidth) / canvas.width;

                            // Add new page if needed (except for first panel)
                            if (i > 0 && yPosition + imgHeight > 270) {
                                pdf.addPage();
                                yPosition = 20;
                            }

                            pdf.addImage(imgData, 'PNG', 20, yPosition, imgWidth, imgHeight);
                            yPosition += imgHeight + 10;
                        }
                    }

                    // Add script on a new page
                    pdf.addPage();
                    pdf.setFontSize(14);
                    pdf.setFont(undefined, 'bold');
                    pdf.text('Script:', 20, 20);

                    pdf.setFontSize(11);
                    pdf.setFont(undefined, 'normal');

                    const scriptText = scriptEl.textContent || scriptEl.innerText;
                    const lines = pdf.splitTextToSize(scriptText, 170);
                    pdf.text(lines, 20, 27);

                    pdf.save('comic-<?php echo $comic['id']; ?>.pdf');
                } catch (error) {
                    console.error('PDF Error:', error);
                    alert('Error generating PDF. Please try again.');
                }
            });
        }
    });
</script>
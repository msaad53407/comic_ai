<div class="history-page-container">
    <div class="history-header">
        <h2>Your Comic History</h2>
        <p>View all your AI-generated comics and scripts</p>
    </div>

    <?php if (empty($comics)): ?>
        <div class="empty-history">
            <span class="material-symbols-outlined empty-icon">auto_stories</span>
            <h3>No Comics Yet</h3>
            <p>Start creating your first comic!</p>
            <a href="/" class="btn-large">Create Comic</a>
        </div>
    <?php else: ?>
        <div class="history-grid">
            <?php foreach ($comics as $comic): ?>
                <div class="history-card" onclick="window.location.href='/comic/<?php echo $comic['id']; ?>'"
                    style="cursor: pointer;">
                    <div class="history-card-image">
                        <?php if (!empty($comic['first_panel'])): ?>
                            <img src="<?php echo htmlspecialchars($comic['first_panel']); ?>"
                                alt="<?php echo htmlspecialchars(substr($comic['prompt'], 0, 50)); ?>">
                        <?php else: ?>
                            <div
                                style="display: flex; align-items: center; justify-content: center; height: 100%; background-color: #e5e7eb;">
                                <span class="material-symbols-outlined" style="font-size: 3rem; opacity: 0.3;">image</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="history-card-content">
                        <div class="history-card-header">
                            <h3><?php echo htmlspecialchars(substr($comic['prompt'], 0, 60)) . (strlen($comic['prompt']) > 60 ? '...' : ''); ?>
                            </h3>
                            <span class="history-date">
                                <?php echo date('M d, Y', strtotime($comic['created_at'])); ?>
                            </span>
                        </div>
                        <div class="history-meta">
                            <span class="meta-badge"><?php echo htmlspecialchars($comic['style']); ?></span>
                            <span class="meta-badge"><?php echo htmlspecialchars($comic['layout']); ?></span>
                        </div>
                        <div class="history-actions" onclick="event.stopPropagation();">
                            <button class="btn-view-details"
                                onclick="window.location.href='/comic/<?php echo $comic['id']; ?>'">
                                <span class="material-symbols-outlined">visibility</span>
                                View Details
                            </button>
                            <button class="btn-download-history" data-comic-id="<?php echo $comic['id']; ?>"
                                data-image="<?php echo htmlspecialchars($comic['image_path']); ?>"
                                data-script="<?php echo htmlspecialchars($comic['script_text'] ?? $comic['prompt']); ?>">
                                <span class="material-symbols-outlined">download</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    // Event delegation for download buttons
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.btn-download-history').forEach(btn => {
            btn.addEventListener('click', async (event) => {
                event.stopPropagation();

                const comicId = btn.dataset.comicId;
                const imagePath = btn.dataset.image;
                const scriptText = btn.dataset.script;

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

                    // Load and add image
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.src = imagePath;

                    await new Promise((resolve, reject) => {
                        img.onload = resolve;
                        img.onerror = reject;
                        setTimeout(reject, 5000);
                    });

                    const canvas = await html2canvas(img, {
                        scale: 2,
                        useCORS: true,
                        allowTaint: true,
                        logging: false
                    });
                    const imgData = canvas.toDataURL('image/png');

                    const imgWidth = 170;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;

                    pdf.addImage(imgData, 'PNG', 20, 30, imgWidth, imgHeight);

                    const scriptY = 30 + imgHeight + 10;
                    pdf.setFontSize(14);
                    pdf.setFont(undefined, 'bold');
                    pdf.text('Script:', 20, scriptY);

                    pdf.setFontSize(11);
                    pdf.setFont(undefined, 'normal');
                    const lines = pdf.splitTextToSize(scriptText, 170);
                    pdf.text(lines, 20, scriptY + 7);

                    pdf.save(`comic-${comicId}.pdf`);
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error generating PDF. Please try again.');
                }
            });
        });
    });
</script>
// Dark Mode Toggle
document.addEventListener("DOMContentLoaded", () => {
  // Initialize dark mode
  const body = document.body;
  const themeToggle = document.getElementById("theme-toggle");
  const currentTheme = localStorage.getItem("theme") || "light";

  if (currentTheme === "dark") {
    body.classList.add("dark");
  }

  // Toggle theme on button click
  if (themeToggle) {
    themeToggle.addEventListener("click", () => {
      body.classList.toggle("dark");
      const newTheme = body.classList.contains("dark") ? "dark" : "light";
      localStorage.setItem("theme", newTheme);
    });
  }

  // Comic Generation Form
  const createForm = document.getElementById("create-form");
  if (!createForm) return;

  const resultArea = document.getElementById("result-area");
  const loadingState = document.getElementById("loading-state");
  const panelsContainer = document.getElementById("comic-panels-container");
  const generatedScript = document.getElementById("generated-script");
  const downloadBtn = document.getElementById("download-btn");
  const loadingMessage = document.querySelector(".loading-state p");
  const enhanceBtn = document.getElementById("enhance-btn");
  const promptInput = document.getElementById("prompt");

  // Prompt Enhancer
  if (enhanceBtn && promptInput) {
    enhanceBtn.addEventListener("click", async () => {
      const originalPrompt = promptInput.value.trim();
      if (!originalPrompt) {
        alert("Please enter a prompt first!");
        return;
      }

      // Loading state
      const originalText = enhanceBtn.innerHTML;
      enhanceBtn.innerHTML = `<span class="material-symbols-outlined" style="animation: spin 1s linear infinite;">sync</span> Enhancing...`;
      enhanceBtn.disabled = true;

      try {
        const response = await fetch("/enhance-prompt", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ prompt: originalPrompt }),
        });

        const result = await response.json();

        if (result.success) {
          promptInput.value = result.enhanced_prompt;
          // Highlight the change
          promptInput.style.borderColor = "#000";
          setTimeout(() => {
            promptInput.style.borderColor = "";
          }, 1000);
        } else {
          alert("Error enhancing prompt: " + (result.error || "Unknown error"));
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Failed to enhance prompt.");
      } finally {
        enhanceBtn.innerHTML = originalText;
        enhanceBtn.disabled = false;
      }
    });
  }

  createForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    // UI Updates moved after validation

    // Get Data
    const formData = new FormData(createForm);
    const panelCountInput = formData.get("panel_count");
    let panelCount = panelCountInput ? parseInt(panelCountInput) : null;

    // Client-side validation
    if (panelCount !== null) {
      if (panelCount < 1) panelCount = 1;
      if (panelCount > 10) {
        alert("Maximum number of panels is 10.");
        return;
      }
    }

    // UI Updates
    createForm.classList.add("hidden");
    resultArea.classList.add("hidden");
    loadingState.classList.remove("hidden");

    const data = {
      prompt: formData.get("prompt"),
      style: formData.get("style"),
      layout: formData.get("layout"),
      panel_count: panelCount,
    };

    try {
      // STEP 1: Generate Script
      loadingMessage.textContent = "Generating script...";

      const scriptResponse = await fetch("/generate-script", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      });

      const scriptResult = await scriptResponse.json();

      if (!scriptResult.success) {
        alert("Error: " + (scriptResult.error || "Script generation failed"));
        loadingState.classList.add("hidden");
        createForm.classList.remove("hidden");
        return;
      }

      // Show script and panel count
      const { comic_id, script, panel_count, panel_descriptions } =
        scriptResult;

      // Render script in preview
      if (typeof marked !== "undefined") {
        generatedScript.innerHTML = marked.parse(script);
      } else {
        generatedScript.textContent = script;
      }

      // Clear previous panels
      panelsContainer.innerHTML = "";

      // Show result area with script
      loadingState.classList.add("hidden");
      resultArea.classList.remove("hidden");

      // Scroll to show script
      resultArea.scrollIntoView({ behavior: "smooth" });

      // STEP 2: Generate Each Panel
      for (let i = 0; i < panel_descriptions.length; i++) {
        const panelData = panel_descriptions[i];

        // Update loading message
        loadingMessage.textContent = `Generating image ${
          i + 1
        } of ${panel_count}...`;
        loadingState.classList.remove("hidden");

        // Create placeholder panel
        const panelDiv = document.createElement("div");
        panelDiv.className = "comic-panel loading-panel";
        panelDiv.innerHTML = `
          <div class="panel-placeholder">
            <div class="spinner"></div>
            <p>Generating panel ${i + 1}...</p>
          </div>
        `;
        panelsContainer.appendChild(panelDiv);

        // Scroll to show new panel
        panelDiv.scrollIntoView({ behavior: "smooth", block: "end" });

        // Generate panel image
        const panelResponse = await fetch("/generate-panel", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            comic_id: comic_id,
            panel_data: panelData,
            style: data.style,
            story_prompt: data.prompt,
            total_panels: panel_count,
          }),
        });

        const panelResult = await panelResponse.json();

        if (panelResult.success) {
          // Replace placeholder with actual image
          const img = document.createElement("img");
          img.src = panelResult.panel.image_url;
          img.alt = `Panel ${panelResult.panel.order}`;
          img.className = "panel-img";
          img.style.opacity = "0";
          img.style.transition = "opacity 0.5s ease-in";

          panelDiv.innerHTML = "";
          panelDiv.classList.remove("loading-panel");
          panelDiv.appendChild(img);

          // Animate in
          setTimeout(() => {
            img.style.opacity = "1";
          }, 50);
        } else {
          panelDiv.innerHTML = `<p class="error">Failed to generate panel ${
            i + 1
          }</p>`;
        }
      }

      // All done
      loadingState.classList.add("hidden");
      createForm.classList.remove("hidden");
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while generating the comic.");
      loadingState.classList.add("hidden");
      createForm.classList.remove("hidden");
    }
  });

  // PDF Download
  if (downloadBtn) {
    downloadBtn.addEventListener("click", async () => {
      try {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({
          orientation: "portrait",
          unit: "mm",
          format: "a4",
        });

        // Add title
        pdf.setFontSize(20);
        pdf.setFont(undefined, "bold");
        pdf.text("ComicAI Generated Comic", 105, 20, { align: "center" });

        let yPosition = 35;
        const panelImages = document.querySelectorAll(".panel-img");

        for (let i = 0; i < panelImages.length; i++) {
          const img = panelImages[i];

          if (img && img.src) {
            try {
              const canvas = await html2canvas(img, {
                scale: 2,
                useCORS: true,
                allowTaint: true,
              });
              const imgData = canvas.toDataURL("image/png");

              // Calculate dimensions to fit A4 width
              const imgWidth = 170; // mm
              const imgHeight = (canvas.height * imgWidth) / canvas.width;

              // Add new page if needed (except for first panel)
              if (i > 0 && yPosition + imgHeight > 270) {
                pdf.addPage();
                yPosition = 20;
              }

              pdf.addImage(imgData, "PNG", 20, yPosition, imgWidth, imgHeight);
              yPosition += imgHeight + 10;
            } catch (error) {
              console.error("Error adding panel to PDF:", error);
            }
          }
        }

        // Add script on a new page
        pdf.addPage();
        pdf.setFontSize(14);
        pdf.setFont(undefined, "bold");
        pdf.text("Script:", 20, 20);

        pdf.setFontSize(11);
        pdf.setFont(undefined, "normal");

        // Get plain text from script (strip HTML if markdown was rendered)
        const scriptText =
          generatedScript.textContent || generatedScript.innerText;
        const lines = pdf.splitTextToSize(scriptText, 170);
        pdf.text(lines, 20, 27);

        // Save PDF
        const timestamp = new Date().toISOString().slice(0, 10);
        pdf.save(`comic-${timestamp}.pdf`);
      } catch (error) {
        console.error("PDF Error:", error);
        alert("PDF library not loaded. Please refresh the page.");
      }
    });
  }
});

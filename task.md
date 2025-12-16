# Task: Restructure Comic Application for Multi-Panel Support

## Database & Model Changes

- [x] Update database schema to support multiple panels per comic
- [x] Add PanelModel to handle panel operations
- [x] Update ComicModel to work with new panel system

## Backend Logic Changes

- [x] Update ComicController to parse script for panel count (max 10)
- [x] Implement panel script parsing in GeminiNanoService
- [x] Generate individual images for each panel
- [x] Save each panel to database with proper ordering

## Frontend UI Changes

- [x] Update home/index.php to display multiple panels vertically
- [x] Update home/detail.php for multi-panel view
- [x] Move script to collapsible dropdown at bottom
- [x] Remove Gallery link from header
- [x] Update CSS for vertical panel layout

## Testing & Verification

- [ ] Test comic generation with multiple panels
- [ ] Verify panel ordering and display
- [ ] Test script dropdown functionality
- [ ] Verify PDF download with all panels

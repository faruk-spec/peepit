<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Operations - Admin Panel</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
</head>
<body>
    <?php require_once __DIR__ . '/../../layouts/admin.php'; ?>

    <div class="admin-content">
        <div class="container">
            <div class="page-header">
                <h1>📦 Bulk Operations</h1>
                <p class="text-muted">Import and export data in bulk</p>
            </div>

            <?php if (has_flash()): ?>
                <div class="alert alert-<?= flash_type() ?>">
                    <?= get_flash() ?>
                </div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;">
                <!-- Import Products -->
                <div class="glass-card">
                    <div class="card-header" style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                        <h3>📥 Import Products</h3>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <p class="text-muted" style="margin-bottom: 20px;">
                            Upload a CSV file to import multiple products at once.
                        </p>
                        
                        <div class="alert alert-info" style="margin-bottom: 20px; padding: 15px; background: rgba(14, 165, 233, 0.1); border-left: 4px solid #0EA5E9;">
                            <strong>CSV Format:</strong><br>
                            <code style="font-size: 0.875rem;">name, description, base_price, capacity, status</code>
                        </div>
                        
                        <form action="<?= url('admin/bulk-operations/import-products') ?>" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                            
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label>Select CSV File</label>
                                <input type="file" name="csv_file" accept=".csv" required 
                                       style="width: 100%; padding: 10px; border: 2px dashed #cbd5e1; border-radius: 8px;">
                            </div>
                            
                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                📥 Import Products
                            </button>
                        </form>
                        
                        <a href="<?= url('admin/bulk-operations/export-products') ?>" 
                           class="btn btn-secondary" 
                           style="width: 100%; margin-top: 10px; display: inline-block; text-align: center;">
                            📄 Download Sample CSV
                        </a>
                    </div>
                </div>

                <!-- Export Products -->
                <div class="glass-card">
                    <div class="card-header" style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                        <h3>📤 Export Products</h3>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <p class="text-muted" style="margin-bottom: 20px;">
                            Export all products to a CSV file for backup or editing.
                        </p>
                        
                        <div class="stats-row" style="display: flex; justify-content: space-around; margin-bottom: 20px; padding: 15px; background: rgba(16, 185, 129, 0.1); border-radius: 8px;">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold; color: #10B981;">
                                    <?= $product_count ?? 0 ?>
                                </div>
                                <div style="font-size: 0.875rem; color: #64748b;">Products</div>
                            </div>
                        </div>
                        
                        <a href="<?= url('admin/bulk-operations/export-products') ?>" 
                           class="btn btn-success" 
                           style="width: 100%; background: #10B981;">
                            📤 Export to CSV
                        </a>
                    </div>
                </div>

                <!-- Export Orders -->
                <div class="glass-card">
                    <div class="card-header" style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                        <h3>📤 Export Orders</h3>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <p class="text-muted" style="margin-bottom: 20px;">
                            Export all orders with customer information.
                        </p>
                        
                        <div class="stats-row" style="display: flex; justify-content: space-around; margin-bottom: 20px; padding: 15px; background: rgba(14, 165, 233, 0.1); border-radius: 8px;">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold; color: #0EA5E9;">
                                    <?= $order_count ?? 0 ?>
                                </div>
                                <div style="font-size: 0.875rem; color: #64748b;">Orders</div>
                            </div>
                        </div>
                        
                        <a href="<?= url('admin/bulk-operations/export-orders') ?>" 
                           class="btn btn-primary" 
                           style="width: 100%;">
                            📤 Export to CSV
                        </a>
                    </div>
                </div>

                <!-- Export Customers -->
                <div class="glass-card">
                    <div class="card-header" style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                        <h3>📤 Export Customers</h3>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <p class="text-muted" style="margin-bottom: 20px;">
                            Export all customer data to CSV format.
                        </p>
                        
                        <div class="stats-row" style="display: flex; justify-content: space-around; margin-bottom: 20px; padding: 15px; background: rgba(245, 158, 11, 0.1); border-radius: 8px;">
                            <div class="text-center">
                                <div style="font-size: 1.5rem; font-weight: bold; color: #F59E0B;">
                                    <?= $customer_count ?? 0 ?>
                                </div>
                                <div style="font-size: 0.875rem; color: #64748b;">Customers</div>
                            </div>
                        </div>
                        
                        <a href="<?= url('admin/bulk-operations/export-customers') ?>" 
                           class="btn btn-warning" 
                           style="width: 100%; background: #F59E0B; color: white;">
                            📤 Export to CSV
                        </a>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="glass-card" style="margin-top: 30px;">
                <div class="card-header" style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
                    <h3>📚 Instructions & Best Practices</h3>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        <div>
                            <h4 style="color: #0EA5E9; margin-bottom: 10px;">📥 Importing Data</h4>
                            <ul style="color: #64748b; line-height: 1.8;">
                                <li>Always use the provided CSV template</li>
                                <li>Ensure data is properly formatted</li>
                                <li>Check for duplicate entries</li>
                                <li>Test with a small batch first</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 style="color: #10B981; margin-bottom: 10px;">📤 Exporting Data</h4>
                            <ul style="color: #64748b; line-height: 1.8;">
                                <li>Exports include all database fields</li>
                                <li>Files are named with current date</li>
                                <li>Use exports for backups</li>
                                <li>Compatible with Excel and Google Sheets</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 style="color: #F59E0B; margin-bottom: 10px;">⚠️ Important Notes</h4>
                            <ul style="color: #64748b; line-height: 1.8;">
                                <li>Large imports may take time</li>
                                <li>Always backup before bulk import</li>
                                <li>Verify data after import</li>
                                <li>Keep exported files secure</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

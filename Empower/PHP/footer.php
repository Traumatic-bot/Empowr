<footer>
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1;"></div>

        <div style="text-align: center;">
            <a href="terms_conditions.php" style="color: black; text-decoration: none; text-alignment: center;">Terms &
                Conditions</a> ｜
            <a href="privacy_policy.php" style="color: black; text-decoration: none; text-alignment: center;">Privacy
                Policy</a>
        </div>

        <div style="flex: 1; text-align: right; padding-right: 15px;">
            <?php if (isLoggedIn()): ?>
            <form method="post" action="set_font_scale.php" style="display: inline;">
                <label for="fontScaleSelect" style="font-size: 14px;">Text Size:</label>
                <select name="scale" id="fontScaleSelect" onchange="this.form.submit()"
                    style="padding: 4px 8px; border-radius: 4px;">
                    <option value="2.00" <?php echo ($_SESSION['font_scale'] ?? 1.00) == 2.00 ? 'selected' : ''; ?>>200%
                    </option>
                    <option value="1.75" <?php echo ($_SESSION['font_scale'] ?? 1.00) == 1.75 ? 'selected' : ''; ?>>175%
                    </option>
                    <option value="1.50" <?php echo ($_SESSION['font_scale'] ?? 1.00) == 1.50 ? 'selected' : ''; ?>>150%
                    </option>
                    <option value="1.25" <?php echo ($_SESSION['font_scale'] ?? 1.00) == 1.25 ? 'selected' : ''; ?>>125%
                    </option>
                    <option value="1.00" <?php echo ($_SESSION['font_scale'] ?? 1.00) == 1.00 ? 'selected' : ''; ?>>100%
                    </option>
                </select>
            </form>
            <?php endif; ?>
        </div>
    </div>
</footer>
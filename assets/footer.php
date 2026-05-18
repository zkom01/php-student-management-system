    <footer>
        <p>&copy; <?php echo date("Y"); ?> Vysoká škola ZKOM. Všechna práva vyhrazena.</p>
    </footer>

    <button id="scrollTopBtn" class="btn btn-secondary" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" title="Zpět nahoru">
        &#8679;
    </button>

    <script>
        const btn = document.getElementById('scrollTopBtn');
        window.addEventListener('scroll', () => {
            btn.classList.toggle('show', window.scrollY > 300);
        });
        
    </script>

</body>
</html>
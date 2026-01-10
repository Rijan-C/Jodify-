<!-- /includes/head.php -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
  href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@500;600;700&display=swap"
  rel="stylesheet">

<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
  href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@500;600;700&display=swap"
  rel="stylesheet">



<!-- Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Tailwind Config -->
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          nepal: {
            primary: '#9D2235',       // Deep Crimson
            secondary: '#C9A227',     // Warm Gold
            bg: '#FAF7F2',            // Soft Ivory
            text: '#2B2B2B',          // Charcoal
            border: '#E5E5E5'         // Light Gray
          }
        },
        fontFamily: {
          logo: ['"Playfair Display"', 'serif'],   // For logos
          heading: ['"Playfair Display"', 'serif'], // Headings
          body: ['Inter', 'sans-serif'],            // Paragraphs & normal text
          button: ['Inter', 'sans-serif']           // Buttons
        }
      }
    }
  }
</script>
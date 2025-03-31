<?php require("views/partials/head.php") ?>
<?php require("views/partials/nav.php") ?>
<?php require("views/partials/banner.php") ?>

<main>
  <div class="mx-auto max-w-7xl my-6 px-4 py-1 sm:px-6 lg:px-8">
    <p><?= htmlspecialchars($note['body']); ?></p>
  </div>

  <div class="mx-auto max-w-7xl my-6 px-4 py-1 sm:px-6 lg:px-8">
    <a href="/notes" class="text-blue-500 underline">Back to all notes...</a>
  </div>
</main>

<?php require("views/partials/footer.php") ?>
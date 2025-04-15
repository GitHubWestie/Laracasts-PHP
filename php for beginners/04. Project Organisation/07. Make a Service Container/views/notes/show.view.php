<?php require(base_path("views/partials/head.php")) ?>
<?php require(base_path("views/partials/nav.php")) ?>
<?php require(base_path("views/partials/banner.php")) ?>

<main class="mx-auto max-w-7xl my-6 px-4 py-1 sm:px-6 lg:px-8">
  <div >
    <p><?= htmlspecialchars($note['body']); ?></p>
  </div>

  <div class="mt-6">
    <a href="/notes" class="text-blue-500 underline">Back to all notes...</a>
  </div>
  
  <form class="mt-6" method="POST">
    <input type="hidden" name="_method" value="DELETE">
    <input type="hidden" name="id" value="<?= $note['id'] ?>">
    <button class="text-sm text-red-500">Delete</button>
  </form>
</main>

<?php require(base_path("views/partials/footer.php")) ?>
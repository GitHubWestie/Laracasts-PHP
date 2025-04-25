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
  
  <div class="mt-6">
    <a href="/note/edit?id=<?= $note['id'] ?>" class="rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">Edit</a>
  </div>

  <form class="mt-6" method="POST">
    <input type="hidden" name="_method" value="DELETE">
    <input type="hidden" name="id" value="<?= $note['id'] ?>">
    <button class="text-sm text-red-500">Delete</button>
  </form>
</main>

<?php require(base_path("views/partials/footer.php")) ?>
const { configureWorkflow, startWorkflow } = await import('@replit/workflows-sdk');

async function main() {
  try {
    await configureWorkflow({
      name: "Run Server",
      primary: true,
      run: "nix-shell -p php82 php82Packages.composer --run \"composer install && php -S 0.0.0.0:8000 router.php\"",
      stop: "pkill -f 'php -S 0.0.0.0:8000'",
    });
    console.log("Workflow configured");
    await startWorkflow({ name: "Run Server" });
    console.log("Workflow started");
  } catch (e) {
    console.error(e);
    process.exit(1);
  }
}

main();

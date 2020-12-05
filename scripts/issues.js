async function fetchAllIssues() {
    let url = 'issues.php/issues'
        // Try catch implementation 
    try {
        const response = await fetch(url);

    } catch (error) {
        console.log(error.message)
    }
}
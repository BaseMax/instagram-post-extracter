import instaloader

L = instaloader.Instaloader()
L.login("max.base", "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx")
L.save_session_to_file()

profile = instaloader.Profile.from_username(L.context, "abbasi.restaurant")

for post in profile.get_posts():
    print(f"{post.date} - {post.url} - {post.caption[:50]}")


public function getDownloadableVideoUrl(Request $request)
    {

        $instagram_url = $request->input('url');
        $parsed_url = $this->prepareInstagramJsonUrl($instagram_url);

        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
        ])->get($parsed_url);

        if ($response->ok()) {
            $json_data = $response->json();

            if (isset($json_data['graphql']['shortcode_media']['video_url'])) {
                $video_url = $json_data['graphql']['shortcode_media']['video_url'];

                return response()->json([
                    'status' => 'success',
                    'video_url' => $video_url,
                ]);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unable to find the video URL.',
        ], 400);
    }


    private function prepareInstagramJsonUrl($instagram_url)
    {
        if (strpos($instagram_url, '?') !== false) {
            return $instagram_url . '&__a=1&__d=dis';
        }
        return $instagram_url . '?__a=1&__d=dis';
    }

import requests
import re
import json
from bs4 import BeautifulSoup


class JsonObject:
    def to_json(self):
        return json.dumps(self, default=lambda item: item.__dict__, sort_keys=True, indent=4)


class u_torrent_monitor:
    # constants
    def __init__(self):
        self.HOST = 'http://www.MYDOMAIN.COM/'
        self.UPDATE = '/api/php/update_status'
        self.GET = '/api/php/active_torrents'
        self.DEACTIVEATE = '/api/php/deactive_torrent'
        self.UTORRENT_URL = 'http://%s:%s/gui/' % ('192.168.1.47', '7070')
        self.UTORRENT_URL_TOKEN = '%stoken.html' % self.UTORRENT_URL
        self.UTORRENT_URL_LIST = '%s?list=1' % self.UTORRENT_URL
        self.UTORRENT_URL_GET = '%s?action=getfiles&hash=' % self.UTORRENT_URL
        self.REGEX_UTORRENT_TOKEN = r'<div[^>]*id=[\"\']token[\"\'][^>]*>([^<]*)</div>'
        self.HEADERS = {'User-Agent': 'Mozilla/5.0'}
        self.JSON_HEADERS = {'User-Agent': 'Mozilla/5.0', 'content-type': 'application/json'}
        self.TPB = ("TPB", "div", "download", "Get this torrent")
        self.KAT = ("KAT", "a", "kaGiantButton", "Magnet link")
        self.auth = None
        self.token = None
        self.cookies = None
        self.get_cookies()

    def get_cookies(self):
        self.auth = requests.auth.HTTPBasicAuth('uid', 'password')
        r = requests.get(self.UTORRENT_URL_TOKEN, auth=self.auth)
        self.token = re.search(self.REGEX_UTORRENT_TOKEN, r.text).group(1)
        self.cookies = dict(GUID=r.cookies['GUID'])

    # get active torrents from api
    def get_torrents(self):
        URL = self.HOST + self.GET
        response = requests.get(URL, headers=self.HEADERS).content
        torrents = json.loads(response)
        return torrents

    # if direct get dom of torrent site
    def get_soup(self, url):
        response = requests.get(url, headers=self.HEADERS).content
        return BeautifulSoup(response, "html.parser")

    # add torrent to uTorrent
    def add_torrent(self, url):
        params = {'action': 'add-url', 's': url, 'token': self.token}
        r = requests.post(url=self.UTORRENT_URL, auth=self.auth, cookies=self.cookies,
                          params=params)

    # tell the api you've taken care of the torrent
    def deactivate_torrent(self, _id="0"):
        url = self.HOST + self.DEACTIVEATE
        requests.post(url, params={"id": _id}, headers=self.HEADERS)

    # create a json array of current downloads
    def create_torrent_json(self):
        params = {'token': self.token}
        data = requests.get(url=self.UTORRENT_URL_LIST, auth=self.auth, cookies=self.cookies,
                            params=params)
        r = json.loads(data.text)
        torrent_list = r['torrents']
        new_list = []
        table = JsonObject()

        for _torrent in torrent_list:
            tbl = table.tbl = JsonObject()
            tbl.hash = _torrent[0]
            tbl.name = _torrent[2]

            try:
                tbl.complete = re.findall("\d+\.\d+", _torrent[21])[0]
            except:
                tbl.complete = 0.0

            new_list.append(tbl)
        return ','.join(obj.to_json() for obj in new_list)

    # send status update to api
    def update_torrent_status(self, items):
        url = self.HOST + self.UPDATE
        params = '{"myData":[' + items + ']}'
        requests.post(url, data=params, headers=self.JSON_HEADERS)

    # logic to decide if KAT or TPB
    def get_page_values(self, torrent_url):
        if torrent_url.startswith('https://kat.cr/'):
            return self.KAT
        elif torrent_url.startswith('https://thepiratebay'):
            return self.TPB
        return None

    # check if this is the correct link
    def validate_link(self, link, page_type, title):
        if page_type == "KAT":
            if link["title"] == title:
                return link['href']
        else:
            if link.find('a').get('title') == title:
                return link.find('a').get('href')

        return None

    # scrape torrent site
    def scrape_site(self, url):
        page_values = self.get_page_values(url)

        if page_values:
            soup = self.get_soup(url)
            links = soup.findAll(page_values[1], {"class": page_values[2]})
            for link in links:
                magnet = self.validate_link(link, page_values[0], page_values[3])

                if magnet:
                    return magnet

        return None

    def main(self):
        tor_list = self.get_torrents()
        print tor_list
        for torrent in tor_list:
            if torrent['link'] not in ['', '?']:
                link = torrent['link']
                _id = torrent['_id']

                if torrent['link_type'] != 'magnet':
                    link = self.scrape_site(torrent['link'])

                self.add_torrent(link)
                self.deactivate_torrent(_id)

        self.update_torrent_status(self.create_torrent_json())


u_torrent_monitor().main()

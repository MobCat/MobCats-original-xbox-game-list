//env/go version go1.20.3 windows/amd64
//Milenko
//MobCat
//2023-2034

// This is a minimalized version of the full XBE deGOder tool
// This tool will only output minimal info about your input xbe file in json format
// You can refine the output with basic string parsing after you have ingested the json
// or you can edit this code to change the output
// But if you want more info about an xbe then this basic tool provides, please use the full XBE deGOder tool.
// This minimalized version was made to keep updating basic and core features easy and quick,
// Then when all the bugs are worked out and the feature is fully built it can be ported over to the full deGOder tool.

// This tool is desined to just vomit json into the terminal
// XBEJson.exe default.xbe
// This was done so tools like pythons run subprocess can just capture and decode the json from the terminal, instead of doing expensive file I/O operations
// If for some reason you want a json file, on windows you can just 
// XBEJson.exe default.xbe > file.json
// On mac, linux or etc, you can pipe | the output to your console text editor of choice.
// Please note, when using > on windows it will try to save the text file as ascii, however this tool outputs UFT16
// It will work most of the time until you hit a Japanese title name or something like that.
// Hence why just capturing the raw terminal with whatever tool your running this with is better.

// Please also note, this is like the 3rd go script I have ever made, it is absolute hot trash. But it functions. Just has way to many hard coded vars and fmtprints.

package main

import (
	"encoding/binary" //
	"encoding/hex"    // Convert title id to sn
	"fmt"             // printing and formaing strings
	"os"              // read/write os level stuff
	"strings"         // "strings"
	"unicode/utf16"   // So our title names are actually correct and not broken from bad ascii converts.
	"strconv"         //
	"time"            // Convert Epoch timestrings
)

type XBEHeader struct {
	MagicNumber                 uint32
	DigitalSignature            [256]byte
	BaseAddress                 uint32
	SizeOfHeaders               uint32
	SizeOfImage                 uint32
	SizeOfImageHeader           uint32
	TimeDate                    uint32
	CertificateAddress          uint32
	NumberOfSections            uint32
	SectionHeadersAddress       uint32
	InitializationFlags         uint32
	EntryPoint                  uint32
	TLSAddress                  uint32
	PEStackCommit               uint32
	PEHeapReserve               uint32
	PEHeapCommit                uint32
	PEBaseAddress               uint32
	PESizeOfImage               uint32
	PEChecksum                  uint32
	PETimeDate                  uint32
	DebugPathNameAddress        uint32
	DebugFileNameAddress        uint32
	DebugUnicodeFileNameAddress uint32 // No idea what this is meant to be outside of its size is meant to be "0x0004"
	KernelImageThunkAddress     uint32
	NonKernelImportDirectory    uint32
	NumberOfLibraryVersions     uint32
	LibraryVersionsAddress      uint32
	KernelLibraryVersionAddress uint32
	XAPILibraryVersionAddress   uint32
	LogoBitmapAddress           uint32
	LogoBitmapSize              uint32
	// 0x0178 Unknown1 - The meaning of this field hasn't been figured out yet. It only exists on XBEs built with an XDK version >= 5028.
	// 0x0180 Unknown2 - The meaning of this field hasn't been figured out yet. It only exists on XBEs built with an XDK version >= 5455.
	// Should be able to pull XDK ver from libs table.
}
type XBECertificate struct {
	SizeOfCertificate      uint32
	TimeDate               uint32
	TitleID                uint32
	TitleName              [40]uint16
	AlternateTitleIDs      [16]uint32
	AllowedMedia           uint32
	GameRegion             uint32
	GameRatings            uint32
	DiskNumber             uint32
	Version                uint32
	LANKey                 [16]byte
	SignatureKey           [16]byte
	AlternateSignatureKeys [16][16]byte
}
type XBELibraries struct {
	LibraryName		[8]byte
	MajorVersion	uint16
	MinorVersion	uint16
	BuildVersion	uint16
	LibraryFlags	uint16
}

type Patch struct {
	Name           string `json:"Name"`
	EntryUnpatched string `json:"EntryUnpatched"`
	EntryPatched   string `json:"EntryPatched"`
}

type GameInfo struct {
	TitleID          string  `json:"TitleID"`
	AvailablePatches []Patch `json:"AvailablePatches"`
}

func readXBECertificate(file *os.File, certificateAddress, baseAddress uint32) (*XBECertificate, error) {
	adjustedAddress := int64(certificateAddress - baseAddress)
	_, err := file.Seek(adjustedAddress, 0)
	if err != nil {
		return nil, err
	}

	certificate := &XBECertificate{}
	err = binary.Read(file, binary.LittleEndian, certificate)
	if err != nil {
		return nil, err
	}
	return certificate, nil
}

func readXBEHeader(file *os.File) (*XBEHeader, error) {
	header := &XBEHeader{}
	err := binary.Read(file, binary.LittleEndian, header)
	if err != nil {
		return nil, err
	}
	return header, nil
}

type XBELibraryInfo struct {
	Libraries []*XBELibraries
}

// Dump all libs from xbe
func readXBELibraries(file *os.File, libraryCount int, libraryAddress, baseAddress uint32) (*XBELibraryInfo, error) {
	adjustedAddress := int64(libraryAddress - baseAddress)
	_, err := file.Seek(adjustedAddress, 0)
	if err != nil {
		return nil, err
	}

	info := &XBELibraryInfo{
		Libraries: make([]*XBELibraries, libraryCount),
	}

	for i := 0; i < libraryCount; i++ {
		library := &XBELibraries{}
		err = binary.Read(file, binary.LittleEndian, library)
		if err != nil {
			return nil, err
		}

		info.Libraries[i] = library
	}

	return info, nil
}

// Take the titleID of the XBE and return the available patches
func availablePatches(titleID string) []Patch {
	return []Patch{}
}

// Take the first 2 chars of the entry point and return the xbe type
// This xbe type is the entded target for the xbe to be ran on
// In the moden era, it can be used if a random xbe you found was
// compiled for debug or is a real retal xbe.
func entryPointDecode(entryPoint uint32) string {
	entryPointStr := fmt.Sprintf("%X", entryPoint)[:2]
	if entryPointStr == "E6" {
		return "Beta"
	} else if entryPointStr == "94" {
		return "Debug"
	} else if entryPointStr == "A8" {
		return "Retail"
	} else {
		return "Unknown"
	}
}

// Convert epoch time back to a timestamp string
func epochConvert(intTime uint32) string {
	tFmt := time.Unix(int64(intTime), 0)

	return tFmt.Format("Mon Jan 02 15:04:05 2006")
}

// Stupid long hex flag decoders
// I feel like this is an inefficient use of memory to declare all these vars at once
// but I also have no idea what I'm doing, how go works or how to do this "properly" sooo....
var mediaDecode = map[uint32]string{
	0x00000001: "HDD",                 // Allow xbe to boot from hdd (bypassed with mods)
	0x00000002: "XBOX DVD",            // Normal og xbox pressed dvd
	0x00000004: "Any CD / DVD",        //
	0x00000008: "CD",                  //
	0x00000010: "DVD_5_RO",            // Burn single layer dvd for testing
	0x00000020: "DVD_9_RO",            // Burn dual layer
	0x00000040: "DVD_5_RW",            // Burnt single layer rewritable
	0x00000080: "DVD_9_RW",            // Burnt dual layer rewritable
	0x00000100: "USB Dongle",          // Allow xbe to boot from the ir dongle used for dvd playback
	0x00000200: "Chihiro_MEDIA_BOARD", // Media board used to boot Chihiro arcade games, but also a defult flag for later XDKs. 2002~3+ iirc
	0x40000000: "Unlock HDD",          //
	0x80000000: "NONSECURE_MODE?",     //
	0x00FFFFFF: "MEDIA_MASK?",         //
}
var initDecode = map[uint32]string{
	0x00000000: "No flags set",
	0x00000001: "Mount Utility Drive",  //
	0x00000002: "Format Utility Drive", //
	0x00000004: "64MB RAM limit",       // Limit game from useing more then 64MB of ram on dev kits for testing. Applys to retail consoles aswell
	0x00000008: "Don't setup HDD",      //
	0x80000000: "Unused high bit (8)?", // No idea what this is, but it's flagged on a lot of games.
}
var regionDecode = map[uint32]string{
	0x00000001: "USA / Canada",
	0x00000002: "Japan",
	0x00000004: "PAL",
	0x80000000: "Debug",
}
var XMIDregionDecode = map[uint32]string{
	0x00000001: "A",
	0x00000002: "J",
	0x00000003: "K",
	0x00000004: "E",
	0x00000005: "L",
	//0x00000006: "?", // 6 is missing or unknown. Probs more to do with the fact that these are hex flags, 7W = 4 Pal + 2 Jpn + 1 USA. No valid conbo of flags = 6
	0x00000007: "W",
	//0x80000000: "D", // Debug??
}
// We can't use the decoder for this one cos math I don't understand. mostly cos 3 and 5 are valid and not combos
// US games raiting as US xboxs are the only xboxs with the games parental controls menu where this flag is used.
var ratingDecode = map[uint32]string{
	0x00000000: "RP - ALL",
	0x00000001: "AO - Adult?", // No legit AO games excists as far as we know. we checked.
	0x00000002: "M - Mature",
	0x00000003: "T - Teen",
	0x00000004: "E - Everyone",
	0x00000005: "K-A - Kids to Adults", // Unconfirmed
	0x00000006: "EC - Early Childhood", // Unconfirmed
	0xFFFFFFFF: "Debug?", // Unconfirmed. This seems like a "set all" ver / flag for testing parental controls, more then a debug flag. Unconfirmed though.
}

// Don't need a hole ass function for this, you can just do pubList[intal] on it's own, but this is the only way I know how to do error handling quickly
// without a bs if func.
// Once we have dumped all xbox games and got the list complete, we can move this to a simple pubList[intal]
// We probs also should have the script load this data from an external file so we don't have to re-compile *every* time we need to update this.
// But again this is only an issue for in-dev. Once the list is done, we can just hardcode it and use pubList[intal]
func lookupPublisher(intal string, titleID string) string {
	var pubList = map[string]string{
		"AC": "Acclaim Entertainment",
		"AD": "Andamiro USA Corp.",
		"AH": "ARUSH Entertainment",
		"AQ": "Aqua System",
		"AS": "ASK",
		"AT": "Atlus",
		"AV": "Activision",
		"AW": "Arc System Works",
		"AY": "Aspyr Media",
		"BA": "Bandai",
		"BB": "BigBen Interactive", // Bigben Interactive UK Ltd.?? idk if BigBen or Bigben or if the sub UK Ltd is needed.
		"BL": "Black Box",
		"BM": "BAM! Entertainment",
		"BR": "Broccoli Co.",
		"BS": "Bethesda Softworks",
		"BU": "Bunkasha Co.",
		"BV": "Buena Vista Interactive",
		"BW": "BBC Multimedia",
		"BZ": "Blizzard",
		"CC": "Capcom",
		"CK": "Kemco Corporation",
		"CM": "Codemasters",
		"CV": "Crave Entertainment",
		"DC": "DreamCatcher Interactive",
		"DX": "Davilex",
		"EA": "Electronic Arts (EA)",
		"EC": "Encore inc",
		"EF": "E-Frontier",
		"EL": "Enlight Software",
		"EM": "Empire Interactive",
		"ES": "Eidos Interactive",
		"FI": "Fox Interactive",
		"FL": "Evolved Games",
		"FS": "From Software",
		"GE": "Genki Co.",
		"GV": "Groove Games",
		"HE": "Tru Blu", // (Entertainment division of Home Entertainment Suppliers)
		"HP": "Hip games",
		"HU": "Hudson Soft",
		"HW": "Highwaystar",
		"IA": "Mad Catz Interactive",
		"IF": "Idea Factory",
		"IG": "Infogrames",
		"IL": "Interlex Corporation",
		"IM": "Imagine Media",
		"IO": "Ignition Entertainment",
		"IP": "Interplay Entertainment",
		"IX": "InXile Entertainment",
		"JA": "Jaleco",
		"JW": "JoWooD",
		"KA": "Konami", // Osaka only? Only found on Jikkyou world socker 2002 so far
		"KB": "Kemco",
		"KI": "Kids Station Inc.",
		"KK": "Kiki Co., Ltd.",
		"KN": "Konami",
		"KO": "KOEI",
		"KU": "Kobi and/or GAE", // formerly Global A Entertainment
		"LA": "LucasArts",
		"LS": "Black Bean Games", // Publishing arm of Leader S.p.A.
		"MD": "Metro3D",
		"ME": "Medix",
		"MI": "Microïds",
		"MJ": "Majesco Entertainment",
		"MM": "Myelin Media",
		"MP": "MediaQuest",
		"MS": "Microsoft Game Studios",
		"MW": "Midway Games",
		"MX": "Empire Interactive",
		"NK": "NewKidCo",
		"NL": "NovaLogic",
		"NM": "Namco",
		"OX": "Oxygen Interactive",
		"PC": "Playlogic Entertainment",
		"PL": "Phantagram Co., Ltd.",
		"RA": "Rage",
		"RD": "responDesign",
		"SA": "Sammy",
		"SC": "SCi Games",
		"SE": "SEGA",
		"SN": "SNK",
		"SP": "SouthPeak Interactive",
		"SS": "Simon & Schuster",
		"ST": "Studio 9", // Zillernet, unconfirmed as I can't find solid english info on this title. Like a high res conver scan.
		"SU": "Success Corporation",
		"SW": "Swing! Deutschland",
		"TA": "Takara",
		"TC": "Tecmo",
		"TD": "The 3DO Company", // Or just 3DO
		"TK": "Takuyo",
		"TM": "TDK Mediactive",
		"TQ": "THQ",
		"TS": "Titus Interactive",
		"TT": "Take-Two Interactive Software",
		"US": "Ubisoft",
		"VC": "Victor Interactive Software",
		"VN": "Vivendi Universal", // Just took Interplays publishing rights
		"VU": "Vivendi Universal Games",
		"VV": "Vivendi Universal Games", // PAL Only?
		"WE": "Wanadoo Edition",
		"WR": "Warner Bros. Interactive Entertainment",
		"XI": "XPEC Entertainment and Idea Factory",
		"XK": "Xbox kiosk disk",
		"XL": "Xbox special bundled or live demo disk",
		"XM": "Evolved Games",
		"XP": "XPEC Entertainment",
		"XR": "Panorama",
		"YB": "YBM Sisa", // South-Korea
		"ZD": "Zushi Games", // Formerly Zoo Digital Publishing
	}

	val, ok := pubList[intal]
	if !ok {
		panic(fmt.Sprintf("\n!!!ERROR!!!\nPublisher %q not found for %q", intal, titleID))
	}

	return val
}

// Actual function to decode flags using the maps.
func decodeDIPSwitch(hexFlag uint32, dictionary map[uint32]string) string {
	var components []string
	for bit := 31; bit >= 0; bit-- {
		if flag := uint32(1 << uint(bit)); hexFlag&flag != 0 {
			if component, ok := dictionary[flag]; ok {
				components = append(components, component)
				hexFlag -= flag
			}
		}
	}
	//return components // retunrs the list, as well a list..
	return strings.Join(components, " + ")
}

func main() {
	if len(os.Args) < 2 {
		fmt.Println("Usage: XBEJson <XBE file>")
		return
	}
	file, err := os.Open(os.Args[1])
	if err != nil {
		fmt.Println("Error opening file:", err)
		return
	}
	defer file.Close()

	header, err := readXBEHeader(file)
	if err != nil {
		fmt.Println("Error reading XBE header:", err)
		return
	}

	certificate, err := readXBECertificate(file, header.CertificateAddress, header.BaseAddress)
	if err != nil {
		fmt.Println("Error reading XBE certificate:", err)
		return
	}

	// Gets number of libs used in file
	libCntStr := fmt.Sprintf("%X", header.NumberOfLibraryVersions)
	libCntNum, _ := strconv.ParseInt(libCntStr, 16, 32)
	// Uses that number to decode all of them from the xbe
	libraryInfo, err := readXBELibraries(file, int(libCntNum), header.LibraryVersionsAddress, header.BaseAddress)
	if err != nil {
		fmt.Println("Error reading XBE libraries:", err)
		return
	}
	libBuildvers := []string{}
	if len(libraryInfo.Libraries) > 0 {
		for _, library := range libraryInfo.Libraries {
			libBuildvers = append(libBuildvers, strconv.Itoa(int(library.BuildVersion)))
		}
		// Remove duplicates from libBuildvers
		seen := make(map[string]bool)
		j := 0
		for i, v := range libBuildvers {
			if !seen[v] {
				seen[v] = true
				libBuildvers[j] = libBuildvers[i]
				j++
			}
		}
		libBuildvers = libBuildvers[:j]
	
	} else {
		libBuildvers = []string{}
	}

	// THIS IS NOT HOW YOU BUILD A JSON
	// But im lazy, a noob, and technically this works, and thats the best kind of works.
	fmt.Println("{")
	titleID := fmt.Sprintf("%08X", certificate.TitleID)
	titleIDl := strings.ToLower(titleID)
	fmt.Printf("\"Title_Image\": \"![0x%s](https://raw.githubusercontent.com/MobCat/MobCats-original-xbox-game-list/main/icon/%s-TitleImage.png)\",\n", titleID, titleIDl)
	fmt.Printf("\"Title_ID_HEX\": \"0x%s\",\n", titleID)

	// Not sure if I should build an error catch just for revolt
	// Convert upper 4 of title id
	hexBytes, _ := hex.DecodeString(titleID[:4])
	asciiStr := string(hexBytes)
	// Convert lower 4 of title id
	decNum, _ := strconv.ParseInt(titleID[4:], 16, 32)
	fmt.Printf("\"Serial_Num\": \"%s-%03d\",\n", asciiStr, decNum)

	// XMID Xbox Manufacturing ID? Used to ID what disk you have, more persific then just title ID.
	// Can be found in redump as a Rings Mastering Code
	versionHexStr := fmt.Sprintf("%08X", certificate.Version)
	versionDecimal, _ := strconv.ParseInt(versionHexStr[len(versionHexStr)-2:], 16, 32)
	fmt.Printf("\"XMID\": \"%s%03d%02d%s\",\n", asciiStr, decNum, versionDecimal, XMIDregionDecode[certificate.GameRegion])

	titleName := utf16.Decode(certificate.TitleName[:])
	titleNameStr := strings.TrimRight(string(titleName), "\x00")
	fmt.Printf("\"Title_Name\": \"%s\",\n", titleNameStr)

	// Need error checking.. lame
	// We are only passing titleID for error checking. TODO: Remove this extra input after we find all the errors.
	val := lookupPublisher(asciiStr, titleID)
	fmt.Printf("\"Publisher\": \"%s\",\n", val)

	fmt.Printf("\"Region\": \"(%X) %s\",\n", certificate.GameRegion, decodeDIPSwitch(certificate.GameRegion, regionDecode))

	fmt.Printf("\"Rating\": \"(%d) %s\",\n", certificate.GameRatings, ratingDecode[certificate.GameRatings])

	fmt.Printf("\"Version\": \"0x%08X\",\n", certificate.Version)

	fmt.Printf("\"Media_Type\": \"(0x%08X) %s\",\n", certificate.AllowedMedia, decodeDIPSwitch(certificate.AllowedMedia, mediaDecode))

	fmt.Printf("\"Init_Flags\": \"(0x%08X) %s\",\n", header.InitializationFlags, decodeDIPSwitch(header.InitializationFlags, initDecode))

	fmt.Printf("\"Entry_Point\": \"(0x%X) %s\",\n", header.EntryPoint, entryPointDecode(header.EntryPoint))

	fmt.Printf("\"Cert_Timestamp\": \"%s\",\n", epochConvert(certificate.TimeDate))

	fmt.Printf("\"XDK_Ver\": \"%s\"\n", strings.Join(libBuildvers, ", "))

	fmt.Println("}")

}